<?php
// 1. Always return JSON
header('Content-Type: application/json');
// 2. Suppress HTML errors
error_reporting(E_ALL);
ini_set('display_errors', 0); 

session_start();
require_once 'db_connect.php';

if (!isset($conn) || $conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "DB Connection failed"]);
    exit;
}

// Get POST data
$input = file_get_contents("php://input");
$data = json_decode($input, true);

$fullname = $data['fullname'] ?? '';
$contact = $data['contact'] ?? '';
$address = $data['address'] ?? '';
$payment = $data['payment'] ?? 'COD';
// Use 'cart' array from JS, fallback to 'items'
$items = $data['cart'] ?? $data['items'] ?? [];
$total = floatval($data['total'] ?? 0);
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

// Validation
if (empty($fullname) || empty($contact) || empty($address) || empty($items)) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

$conn->begin_transaction();

try {
    // 1. Check Stock Levels First
    $checkStock = $conn->prepare("SELECT stock, name FROM products WHERE id = ?");
    foreach ($items as $item) {
        $pid = isset($item['id']) ? (int)$item['id'] : 0;
        if ($pid === 0 && isset($item['product_id'])) $pid = (int)$item['product_id'];
        
        $qty = (int)($item['quantity'] ?? 1);

        if ($pid > 0) {
            $checkStock->bind_param("i", $pid);
            $checkStock->execute();
            $res = $checkStock->get_result();
            if ($row = $res->fetch_assoc()) {
                if ($row['stock'] < $qty) {
                    throw new Exception("Insufficient stock for " . $row['name']);
                }
            }
        }
    }
    $checkStock->close();

    // 2. Insert into CART table
    $cartJson = json_encode($items);
    
    $hasUserId = false;
    $chk = $conn->query("SHOW COLUMNS FROM cart LIKE 'user_id'");
    if($chk && $chk->num_rows > 0) $hasUserId = true;
    
    $hasPayment = false;
    $chk2 = $conn->query("SHOW COLUMNS FROM cart LIKE 'payment_method'");
    if($chk2 && $chk2->num_rows > 0) $hasPayment = true;

    $sql = "";
    $stmt = null;

    if ($hasUserId && $hasPayment) {
        $sql = "INSERT INTO cart (fullname, contact, address, cart, total, status, created_at, user_id, payment_method) VALUES (?, ?, ?, ?, ?, 'Pending', NOW(), ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssdis", $fullname, $contact, $address, $cartJson, $total, $userId, $payment);
    } elseif ($hasUserId) {
        $sql = "INSERT INTO cart (fullname, contact, address, cart, total, status, created_at, user_id) VALUES (?, ?, ?, ?, ?, 'Pending', NOW(), ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssdi", $fullname, $contact, $address, $cartJson, $total, $userId);
    } else {
        $sql = "INSERT INTO cart (fullname, contact, address, cart, total, status, created_at) VALUES (?, ?, ?, ?, ?, 'Pending', NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssd", $fullname, $contact, $address, $cartJson, $total);
    }

    if (!$stmt->execute()) {
        throw new Exception("Failed to save order: " . $stmt->error);
    }
    $order_id = $stmt->insert_id;
    $stmt->close();

    // 3. Deduct Stock
    $updateStock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
    foreach ($items as $item) {
        $pid = isset($item['id']) ? (int)$item['id'] : 0;
        if ($pid === 0 && isset($item['product_id'])) $pid = (int)$item['product_id'];
        $qty = (int)($item['quantity'] ?? 1);

        if ($pid > 0) {
            $updateStock->bind_param("ii", $qty, $pid);
            $updateStock->execute();
        }
    }
    $updateStock->close();

    // ---------------------------------------------------------
    // 4. PAYMONGO INTEGRATION (Fixed: Removed Images)
    // ---------------------------------------------------------
    $checkoutUrl = null;

    if (in_array($payment, ['GCash', 'GrabPay'])) {
        
        // !!! REPLACE WITH YOUR ACTUAL PAYMONGO SECRET KEY !!!
        $paymongo_secret_key = 'sk_test_4wnAfmzuwJANdZP9sB8Zxf1o'; 

        // Build Base URL dynamically
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $path = dirname($_SERVER['PHP_SELF']);
        $base_url = $protocol . "://" . $host . $path;
        
        // Prepare Line Items (IMAGES REMOVED TO PREVENT ERRORS)
        $line_items = [];
        foreach ($items as $item) {
            $line_items[] = [
                'name'      => $item['name'],
                'quantity'  => (int)$item['quantity'],
                'amount'    => (int)($item['price'] * 100), // Centavos
                'currency'  => 'PHP'
            ];
        }

        // Delivery Fee Logic
        $itemsTotal = 0;
        foreach ($items as $item) {
            $itemsTotal += ($item['price'] * $item['quantity']);
        }
        $deliveryFee = $total - $itemsTotal;
        
        if ($deliveryFee > 0) {
            $line_items[] = [
                'name'      => 'Delivery Fee',
                'quantity'  => 1,
                'amount'    => (int)($deliveryFee * 100),
                'currency'  => 'PHP'
            ];
        }
        
        $payload = [
            'data' => [
                'attributes' => [
                    'billing' => [
                        'name'  => $fullname,
                        'email' => $_SESSION['email'] ?? 'customer@example.com',
                        'phone' => $contact
                    ],
                    'line_items' => $line_items,
                    'payment_method_types' => [strtolower($payment) === 'gcash' ? 'gcash' : 'grab_pay'],
                    'description' => "Order #$order_id",
                    'success_url' => $base_url . "/my_orders.php?msg=payment_success",
                    'cancel_url'  => $base_url . "/cart.php?msg=payment_cancelled",
                    'send_email_receipt' => true
                ]
            ]
        ];

        $ch = curl_init('https://api.paymongo.com/v1/checkout_sessions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($paymongo_secret_key)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $apiResponse = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300 && isset($apiResponse['data']['attributes']['checkout_url'])) {
            $checkoutUrl = $apiResponse['data']['attributes']['checkout_url'];
        } else {
            // Log error
            $errorDetail = json_encode($apiResponse);
            throw new Exception("Payment Error: " . ($apiResponse['errors'][0]['detail'] ?? $errorDetail));
        }
    }

    $conn->commit();

    $responseArr = [
        "status" => "success", 
        "message" => "Order placed successfully!", 
        "order_id" => $order_id
    ];

    if ($checkoutUrl) {
        $responseArr['checkout_url'] = $checkoutUrl;
    }

    echo json_encode($responseArr);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>