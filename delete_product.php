<?php
// 1. ENABLE ERROR REPORTING (Helps fix the 500 error)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. CONNECT TO DATABASE
include 'db_connect.php'; 

// 3. CHECK IF ID IS PRESENT
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // VALIDATE ID (Make sure it's a number to prevent SQL injection)
    if (!is_numeric($product_id)) {
        die("Invalid ID");
    }

    try {
        // STEP A: COPY to 'recently_deleted'
        // NOTE: This requires a table named 'recently_deleted' with the SAME columns as 'products'
        $copy_query = "INSERT INTO recently_deleted SELECT * FROM products WHERE id = ?";
        $stmt_copy = $conn->prepare($copy_query);
        $stmt_copy->bind_param("i", $product_id);
        $stmt_copy->execute();
        $stmt_copy->close();

        // STEP B: DELETE from 'products'
        $delete_query = "DELETE FROM products WHERE id = ?";
        $stmt_delete = $conn->prepare($delete_query);
        $stmt_delete->bind_param("i", $product_id);
        
        if ($stmt_delete->execute()) {
            // STEP C: REDIRECT back to the main page
            header("Location: practiceaddproduct.php?msg=ProductMovedToBin");
            exit();
        } else {
            echo "Error deleting product.";
        }
        $stmt_delete->close();

    } catch (Exception $e) {
        // This will print the specific error if something goes wrong
        echo "Error: " . $e->getMessage();
    }

} else {
    // If no ID is provided, go back
    header("Location: practiceaddproduct.php?error=NoID");
    exit();
}

$conn->close();
?>