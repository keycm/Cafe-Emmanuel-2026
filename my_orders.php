<?php
// my_orders.php
session_start();

// 1. Enable Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Check Login
if (!isset($_SESSION['user_id'])) { 
    header('Location: index.php?action=login'); 
    exit; 
}

$fullname = $_SESSION['fullname'] ?? '';
$userId = (int)($_SESSION['user_id'] ?? 0);

// 3. Database Connection
include 'config.php'; // Changed to config.php for consistency

// Fallback if config isn't used
if (!isset($conn)) {
    include 'db_connect.php';
}

// Check if connection worked
if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed.");
}

// 4. Fetch Orders
$stmt = $conn->prepare("
    SELECT id, fullname, contact, address, total, status, created_at, cart, cancel_reason 
    FROM cart 
    WHERE (user_id = ? OR (user_id IS NULL AND fullname = ?)) 
    ORDER BY id DESC
");

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param('is', $userId, $fullname);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="logo.png">
    <title>My Orders - Cafe Emmanuel</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Akaya+Telivigala&family=Archivo+Black&family=Archivo+Narrow:wght@400;700&family=Birthstone+Bounce:wght@500&family=Inknut+Antiqua:wght@600&family=Playfair+Display:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">

    <style>
        /* --- Global Variables --- */
        :root {
            --primary-color: #B95A4B;
            --primary-dark: #9C4538;
            --secondary-color: #3C2A21;
            --text-color: #333;
            --heading-color: #1F1F1F;
            --white: #FFFFFF;
            --bg-light: #FCFBF8;
            --border-color: #EAEAEA;
            --footer-bg-color: #1a120b;
            --footer-text-color: #ccc;
            --footer-link-hover: #FFC94A;
            
            --font-logo-cafe: 'Archivo Black', sans-serif;
            --font-logo-emmanuel: 'Birthstone Bounce', cursive;
            --font-nav: 'Inknut Antiqua', serif;
            --font-heading: 'Playfair Display', serif;
            --font-body: 'Lato', sans-serif;
            
            --nav-height: 90px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-body); color: var(--text-color); background-color: var(--bg-light); line-height: 1.7; padding-top: var(--nav-height); display: flex; flex-direction: column; min-height: 100vh; }
        
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        a { text-decoration: none; transition: 0.3s; color: inherit; }
        
        /* --- Header --- */
        .header { 
            position: fixed; width: 100%; top: 0; z-index: 1000; height: var(--nav-height); 
            background: rgba(26, 18, 11, 0.95); backdrop-filter: blur(10px); 
            border-bottom: 1px solid rgba(255, 255, 255, 0.1); 
        }
        .navbar { display: flex; justify-content: space-between; align-items: center; height: 100%; }
        
        .nav-logo { display: flex; align-items: center; color: var(--white); }
        .logo-cafe { font-family: var(--font-logo-cafe); font-size: 32px; letter-spacing: -1px; }
        .logo-emmanuel { font-family: var(--font-logo-emmanuel); font-size: 38px; margin-left: 8px; color: var(--primary-color); font-weight: 500; }
        
        .nav-menu { display: flex; gap: 2.5rem; list-style: none; }
        .nav-link { font-family: var(--font-nav); font-size: 15px; font-weight: 500; color: rgba(255,255,255,0.9); position: relative; }
        .nav-link::after { content: ''; position: absolute; width: 0; height: 2px; bottom: -4px; left: 0; background-color: var(--footer-link-hover); transition: width 0.3s; }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        .nav-link:hover, .nav-link.active { color: var(--footer-link-hover); }
        
        .nav-right-cluster { display: flex; align-items: center; gap: 1rem; }
        
        /* New Styles for Nav Icons & Avatar */
        .nav-icon-btn {
            position: relative; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;
            border-radius: 50%; background: rgba(255, 255, 255, 0.1); color: var(--white); font-size: 1.1rem; transition: all 0.3s ease;
        }
        .nav-icon-btn:hover { background: var(--footer-link-hover); color: var(--secondary-color); transform: translateY(-2px); }

        .user-avatar {
            width: 45px; height: 45px; border-radius: 50%; object-fit: cover;
            border: 2px solid var(--primary-color); transition: transform 0.3s ease; background: #fff;
        }
        .profile-dropdown:hover .user-avatar { transform: scale(1.05); box-shadow: 0 0 10px rgba(255, 255, 255, 0.3); }
        
        .profile-dropdown { position: relative; cursor: pointer; display: flex; align-items: center; }
        .profile-dropdown::after { content: ''; position: absolute; top: 100%; left: 0; width: 100%; height: 50px; background: transparent; }

        .profile-menu { display: none; position: absolute; right: 0; top: 140%; background: var(--white); min-width: 200px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); overflow: hidden; z-index: 1001; }
        .profile-dropdown:hover .profile-menu { display: block; }
        .profile-menu a { display: block; padding: 12px 20px; color: var(--text-color); font-size: 0.95rem; border-bottom: 1px solid var(--border-color); }
        .profile-menu a:hover { background: #f8f9fa; color: var(--primary-color); }
        
        .hamburger { display: none; cursor: pointer; }
        .bar { display: block; width: 25px; height: 3px; margin: 5px auto; background-color: var(--white); transition: 0.3s; }

        /* --- Main Content --- */
        main { flex: 1; padding: 4rem 0; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .page-title { font-family: var(--font-heading); font-size: 2.5rem; color: var(--heading-color); margin: 0; }
        
        .back-button { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: var(--white); border: 1px solid var(--border-color); border-radius: 50px; cursor: pointer; font-weight: 600; box-shadow: 0 2px 5px rgba(0,0,0,0.05); font-family: var(--font-body); }
        .back-button:hover { background: var(--primary-color); color: var(--white); border-color: var(--primary-color); }

        /* --- Orders Table --- */
        .orders-card { background: var(--white); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid var(--border-color); }
        .table-responsive { overflow-x: auto; }
        .orders-table { width: 100%; border-collapse: collapse; min-width: 800px; }
        .orders-table th { background-color: #f9f9f9; padding: 1.2rem; text-align: left; font-family: var(--font-nav); font-size: 0.9rem; color: var(--secondary-color); text-transform: uppercase; border-bottom: 2px solid var(--border-color); }
        .orders-table td { padding: 1.2rem; border-bottom: 1px solid var(--border-color); vertical-align: middle; color: #555; }
        .orders-table tr:hover { background-color: #fcfcfc; }

        /* Pills & Buttons */
        .pill { padding: 6px 14px; border-radius: 50px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; display: inline-block; }
        .pending { background: #fff8e1; color: #f57c00; border: 1px solid #ffe0b2; }
        .confirmed, .processing { background: #e3f2fd; color: #1976d2; border: 1px solid #bbdefb; }
        .out_for_delivery { background: #e8eaf6; color: #3f51b5; border: 1px solid #c5cae9; }
        .delivered, .completed { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        .cancelled { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }

        .btn { padding: 8px 16px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.9rem; transition: all 0.3s; display: inline-block; }
        .btn-view { background-color: var(--white); border: 1px solid var(--border-color); color: var(--text-color); }
        .btn-view:hover { border-color: var(--primary-color); color: var(--primary-color); background: #fff5f5; }
        .btn-cancel-order { background-color: var(--white); border: 1px solid #dc3545; color: #dc3545; margin-left: 5px; }
        .btn-cancel-order:hover { background-color: #dc3545; color: var(--white); }
        .btn-receive { background-color: #2e7d32; color: var(--white); margin-left: 5px; }
        .btn-receive:hover { background-color: #1b5e20; }

        .msg { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; text-align: center; }
        .msg-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .msg-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* --- Footer --- */
        .footer { background-color: var(--footer-bg-color); color: var(--footer-text-color); padding-top: 4rem; font-size: 0.95rem; margin-top: auto; }
        .footer-content { display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 3rem; padding-bottom: 3rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .footer-brand h3 { color: var(--white); font-family: var(--font-logo-cafe); font-size: 1.8rem; margin-bottom: 1rem; }
        .footer-brand p { opacity: 0.7; margin-bottom: 1.5rem; line-height: 1.7; }
        .socials { display: flex; gap: 15px; }
        .social-link { width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; transition: 0.3s; color: var(--white); }
        .social-link:hover { background: var(--footer-link-hover); color: var(--secondary-color); }
        .footer-col h4 { color: var(--white); font-size: 1.1rem; margin-bottom: 1.5rem; font-family: var(--font-body); font-weight: bold; }
        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 0.8rem; }
        .footer-links a { color: rgba(255,255,255,0.6); transition: 0.3s; }
        .footer-links a:hover { color: var(--footer-link-hover); padding-left: 5px; }
        .copyright { text-align: center; padding: 1.5rem 0; opacity: 0.5; font-size: 0.85rem; }

        /* --- Modals --- */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; align-items: center; justify-content: center; backdrop-filter: blur(5px); animation: fadeIn 0.3s; }
        .modal-box { background: #ffffff; padding: 30px; border-radius: 20px; width: 90%; max-width: 500px; position: relative; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); animation: slideUp 0.3s ease; }
        .modal-close { position: absolute; top: 20px; right: 25px; font-size: 1.5rem; color: #ccc; cursor: pointer; transition: 0.2s; }
        .modal-close:hover { color: var(--primary-color); }
        .modal-title { font-family: var(--font-heading); font-size: 1.8rem; margin-bottom: 1.5rem; color: var(--heading-color); border-bottom: 1px solid var(--border-color); padding-bottom: 10px; }
        
        .info-group { margin-bottom: 15px; }
        .info-group strong { display: block; color: #888; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 4px; }
        .info-group p { font-size: 1rem; color: var(--text-color); margin: 0; font-weight: 500; }
        
        .modal-items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .modal-items-table th, .modal-items-table td { padding: 10px 5px; text-align: left; border-bottom: 1px solid #f0f0f0; font-size: 0.95rem; }
        .modal-items-table th { font-weight: 600; color: var(--secondary-color); }
        .modal-items-table .total-row td { font-weight: 700; font-size: 1.1rem; color: var(--primary-color); border-top: 2px solid var(--border-color); padding-top: 15px; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 5px; font-size: 0.9rem; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem; font-family: var(--font-body); }
        .modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }

        /* Timeline in Modal */
        .status-timeline { margin-top: 10px; }
        .timeline-row { display: flex; align-items: center; margin-bottom: 8px; }
        .timeline-dot { width: 20px; height: 20px; border-radius: 50%; border: 2px solid #ddd; display: flex; align-items: center; justify-content: center; margin-right: 10px; color: transparent; font-size: 10px; }
        .timeline-dot.done { background: var(--primary-color); border-color: var(--primary-color); color: white; }
        .timeline-line { width: 2px; height: 15px; background: #ddd; margin-left: 9px; margin-bottom: 5px; margin-top: -5px; }
        .timeline-text { font-size: 0.9rem; color: #666; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 768px) {
            .nav-menu, .nav-right-cluster { display: none; }
            .hamburger { display: block; }
            .nav-menu.active { display: flex; flex-direction: column; position: absolute; top: var(--nav-height); left: 0; width: 100%; background: var(--secondary-color); padding: 2rem; text-align: center; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 15px; }
            .orders-table th, .orders-table td { padding: 10px; font-size: 0.85rem; }
        }
    </style>
</head>
<body>

    <header class="header">
        <nav class="navbar container">
            <a href="index.php" class="nav-logo">
                <span class="logo-cafe"><span class="first-letter">C</span>afe</span>
                <span class="logo-emmanuel"><span class="first-letter">E</span>mmanuel</span>
            </a>
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link">Home</a></li>
                <li><a href="product.php" class="nav-link">Menu</a></li>
                <li><a href="about.php" class="nav-link">About</a></li>
                <li><a href="contact.php" class="nav-link">Contact</a></li>
                <li><a href="my_orders.php" class="nav-link active">My Orders</a></li>
            </ul>
            
            <div class="nav-right-cluster">
                <a href="cart.php" class="nav-icon-btn" title="View Cart">
                    <i class="fas fa-shopping-cart"></i>
                </a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="nav-icon-btn" title="Notifications">
                        <?php include 'notification_bell.php'; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['fullname'])): ?>
                    <div class="profile-dropdown">
                        <?php 
                            $profilePic = !empty($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : 'https://ui-avatars.com/api/?name='.urlencode($_SESSION['fullname']).'&background=B95A4B&color=fff';
                        ?>
                        <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile" class="user-avatar">
                        
                        <div class="profile-menu">
                            <a href="profile.php"><i class="fas fa-user"></i> My Profile</a>
                            <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'super_admin'])): ?>
                                <a href="Dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                            <?php endif; ?>
                            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
                        </div>
                    </div>
                <?php else: ?>
                    <button class="login-trigger" onclick="location.href='index.php?action=login'">Login</button>
                <?php endif; ?>
            </div>

            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">My Orders</h1>
                <a href="product.php" class="back-button"><i class="fas fa-arrow-left"></i> Back to Menu</a>
            </div>

            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'cancelled'): ?>
                <div class="msg msg-success">Order cancelled successfully.</div>
            <?php endif; ?>
            <?php if (isset($_GET['err'])): ?>
                <div class="msg msg-error">Action failed. Please try again.</div>
            <?php endif; ?>

            <div class="orders-card">
                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date Placed</th>
                                <th>Items Summary</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($orders->num_rows > 0): ?>
                                <?php while ($o = $orders->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong>#<?php echo str_pad($o['id'], 4, '0', STR_PAD_LEFT); ?></strong></td>
                                        <td><?php echo htmlspecialchars(date('M d, Y h:i A', strtotime($o['created_at']))); ?></td>
                                        <td>
                                            <?php 
                                                $items = json_decode($o['cart'] ?? '[]', true);
                                                if (!is_array($items)) $items = [];
                                                $names = array_map(function($it){ return htmlspecialchars($it['name'] ?? 'Unknown'); }, array_slice($items, 0, 2)); 
                                                echo implode(', ', $names); 
                                                if (count($items) > 2) echo ' + ' . (count($items) - 2) . ' more'; 
                                            ?>
                                        </td>
                                        <td><strong>₱<?php echo number_format((float)$o['total'], 2); ?></strong></td>
                                        <td>
                                            <?php $s = strtolower(str_replace(' ', '_', $o['status'])); ?>
                                            <span class="pill <?php echo $s; ?>"><?php echo htmlspecialchars($o['status']); ?></span>
                                        </td>
                                        <td>
                                            <?php 
                                                $orderData = [
                                                    'id' => $o['id'],
                                                    'fullname' => $o['fullname'],
                                                    'contact' => $o['contact'],
                                                    'address' => $o['address'],
                                                    'created_at' => $o['created_at'],
                                                    'total' => $o['total'],
                                                    'status' => $o['status'],
                                                    'cart' => $o['cart'],
                                                    'cancel_reason' => $o['cancel_reason'] ?? null
                                                ];
                                                $orderJson = htmlspecialchars(json_encode($orderData), ENT_QUOTES, 'UTF-8'); 
                                            ?>
                                            <button class="btn btn-view" onclick="openDetails(this)" data-order='<?php echo $orderJson; ?>'>View</button>
                                            
                                            <?php if (strtolower($o['status']) === 'pending'): ?>
                                                <button class="btn btn-cancel-order" onclick="openCancelModal(<?php echo (int)$o['id']; ?>)">Cancel</button>
                                            <?php elseif (strtolower($o['status']) === 'out for delivery' || strtolower($o['status']) === 'out_for_delivery'): ?>
                                                <form method="POST" action="update_order.php" style="display:inline-block;" onsubmit="return confirm('Confirm that you received this order?');">
                                                    <input type="hidden" name="id" value="<?php echo (int)$o['id']; ?>" />
                                                    <input type="hidden" name="action" value="completed" />
                                                    <button type="submit" class="btn btn-receive">Receive</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6" style="text-align:center; padding:3rem;">You have no orders yet. <a href="product.php" style="color:var(--primary-color); font-weight:bold;">Start Shopping</a></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h3><span style="color:var(--primary-color);">C</span>afe Emmanuel</h3>
                    <p>Your neighborhood destination for exceptional coffee, delicious food, and warm hospitality.</p>
                    <div class="socials">
                        <a href="https://www.facebook.com/profile.php?id=61574968445731" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/cafeemmanuelph/" class="social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="product.php">Menu</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Contact Info</h4>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt" style="margin-right:8px; color:var(--primary-color);"></i> San Antonio Road, Purok Dayat, San Antonio, Guagua, Pampanga, Philippines</li>
                        <li><i class="fas fa-phone" style="margin-right:8px; color:var(--primary-color);"></i> 0995 100 9209</li>
                        <li><i class="fas fa-envelope" style="margin-right:8px; color:var(--primary-color);"></i> emmanuel.cafegallery@gmail.com</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>© 2025 Cafe Emmanuel. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <div id="detailsModal" class="modal-overlay">
        <div class="modal-box">
            <span class="modal-close" onclick="closeDetails()">×</span>
            <h3 class="modal-title">Order Details</h3>
            
            <div id="detailsMeta" style="font-size:0.9rem; color:#666; margin-bottom:15px; text-align:center;"></div>

            <div class="info-group">
                <strong>Order Status</strong>
                <div id="detailsTimeline" class="status-timeline"></div>
            </div>

            <table class="modal-items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="text-align:right;">Qty</th>
                        <th style="text-align:right;">Price</th>
                    </tr>
                </thead>
                <tbody id="detailsItems"></tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="2">Total Amount</td>
                        <td id="detailsTotal" style="text-align:right;"></td>
                    </tr>
                </tfoot>
            </table>
            
            <div id="detailsCancelReason" style="margin-top:15px; padding:10px; background:#ffebee; color:#c62828; border-radius:8px; display:none; font-size:0.9rem;"></div>
        </div>
    </div>

    <div id="cancelModal" class="modal-overlay">
        <div class="modal-box">
            <span class="modal-close" onclick="closeCancelModal()">×</span>
            <h3 class="modal-title">Cancel Order</h3>
            <p style="color:#666; font-size:0.95rem; margin-bottom:1.5rem;">Are you sure you want to cancel this order? This action cannot be undone.</p>
            
            <form method="POST" action="cancel_cart_order.php" onsubmit="return verifyCancel()">
                <input type="hidden" name="order_id" id="cancelOrderId" />
                
                <div class="form-group">
                    <label>Reason for cancellation</label>
                    <select name="reason" class="form-control" required>
                        <option value="">Select a reason</option>
                        <option>Ordered by mistake</option>
                        <option>Found a better price</option>
                        <option>Changed my mind</option>
                        <option>Delivery time is too long</option>
                        <option>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Type "Cancel Order" to confirm</label>
                    <input type="text" name="confirm_text" id="confirmText" class="form-control" placeholder="Cancel Order" required />
                </div>
                
                <div class="modal-actions">
                    <button type="button" onclick="closeCancelModal()" class="btn" style="background:#f0f0f0; color:#333;">Keep Order</button>
                    <button type="submit" class="btn btn-cancel-order">Confirm Cancellation</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // --- Modal Logic ---
        function openCancelModal(id) {
            document.getElementById('cancelOrderId').value = id;
            document.getElementById('confirmText').value = '';
            document.getElementById('cancelModal').style.display = 'flex';
        }
        function closeCancelModal() {
            document.getElementById('cancelModal').style.display = 'none';
        }
        function verifyCancel() {
            return document.getElementById('confirmText').value.trim().toLowerCase() === 'cancel order';
        }

        // --- Timeline Render ---
        function renderTimeline(container, status) {
            const normalized = (status || '').toLowerCase();
            const steps = [
                { key: 'placed', label: 'Order Placed' },
                { key: 'confirmed', label: 'Confirmed' },
                { key: 'processing', label: 'Processing' },
                { key: 'delivered', label: 'Delivered' },
            ];

            let currentIndex = 0;
            if (normalized === 'pending') currentIndex = 0;
            else if (normalized === 'confirmed') currentIndex = 1;
            else if (normalized === 'processing') currentIndex = 2;
            else if (normalized === 'out for delivery' || normalized === 'out_for_delivery') currentIndex = 2; // Still processing logic
            else if (normalized === 'delivered' || normalized === 'completed') currentIndex = 3;

            let html = '';
            steps.forEach((step, idx) => {
                const done = idx <= currentIndex;
                html += `
                    <div class="timeline-row">
                        <div class="timeline-dot ${done ? 'done' : ''}">
                            ${done ? '<i class="fas fa-check"></i>' : ''}
                        </div>
                        <div class="timeline-text" style="font-weight:${done ? 'bold' : 'normal'}">
                            ${step.label}
                        </div>
                    </div>`;
                if (idx < steps.length - 1) {
                    html += '<div class="timeline-line"></div>';
                }
            });
            container.innerHTML = html;
        }

        // --- Open Details Modal ---
        function openDetails(btn) {
            const data = JSON.parse(btn.getAttribute('data-order'));
            let items = [];
            try { items = JSON.parse(data.cart || '[]'); } catch(e) { items = []; }
            
            const tbody = document.getElementById('detailsItems');
            tbody.innerHTML = '';
            let total = 0;

            // Render Timeline
            const timelineContainer = document.getElementById('detailsTimeline');
            renderTimeline(timelineContainer, data.status || 'pending');

            // Render Items
            items.forEach(it => {
                const qty = Number(it.quantity || 1);
                const price = Number(it.price || 0);
                const subtotal = qty * price;
                total += subtotal;
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${it.name}</td>
                    <td style="text-align:right;">${qty}</td>
                    <td style="text-align:right;">₱${price.toFixed(2)}</td>
                `;
                tbody.appendChild(tr);
            });
            
            document.getElementById('detailsTotal').textContent = '₱' + Number(data.total || total).toFixed(2);
            document.getElementById('detailsMeta').textContent = `Order #${String(data.id).padStart(4,'0')} • ${new Date(data.created_at.replace(' ','T')).toLocaleString()}`;
            
            // Cancel reason check
            const cr = document.getElementById('detailsCancelReason');
            if ((data.status||'').toLowerCase() === 'cancelled' && data.cancel_reason) { 
                cr.style.display = 'block'; 
                cr.innerHTML = '<strong>Cancellation Reason:</strong> ' + data.cancel_reason; 
            } else { 
                cr.style.display = 'none'; 
            }
            
            document.getElementById('detailsModal').style.display = 'flex';
        }

        function closeDetails() {
            document.getElementById('detailsModal').style.display = 'none';
        }

        // --- Close modals on outside click ---
        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.style.display = 'none';
            }
        }
        
        // --- Navigation Script (Hamburger) ---
        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('.nav-menu');
        if (hamburger) {
            hamburger.addEventListener('click', () => {
                navMenu.classList.toggle('active');
            });
        }
    </script>
</body>
</html>