<?php
session_start();

// 1. Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Connect to Database using your specific file
require_once 'db_connect.php';

// Check connection
if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . ($conn->connect_error ?? "Database variable missing"));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $action = $_POST['action'];

    // Get the record from recently_deleted (orders)
    $sql = "SELECT * FROM recently_deleted WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($action === 'restore') {
            // Restore back to 'cart' table (based on your code)
            // Make sure the columns here match your current 'cart' table structure
            $insert_sql = "INSERT INTO cart (fullname, contact, address, cart, total, status) VALUES (?, ?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            
            if ($insert_stmt) {
                $insert_stmt->bind_param(
                    "ssssds",
                    $row['fullname'],
                    $row['contact'],
                    $row['address'],
                    $row['cart'],
                    $row['total'],
                    $row['status']
                );
                
                if ($insert_stmt->execute()) {
                    $insert_stmt->close();

                    // Remove from recently_deleted
                    $delete_sql = "DELETE FROM recently_deleted WHERE id = ?";
                    $delete_stmt = $conn->prepare($delete_sql);
                    $delete_stmt->bind_param("i", $id);
                    $delete_stmt->execute();
                    $delete_stmt->close();
                } else {
                    die("Error restoring order: " . $conn->error);
                }
            } else {
                die("Error preparing restore: " . $conn->error);
            }

        } elseif ($action === 'permanent') {
            // Permanently delete
            $delete_sql = "DELETE FROM recently_deleted WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $id);
            $delete_stmt->execute();
            $delete_stmt->close();
        }
    }

    $stmt->close();
    header("Location: recently_deleted.php");
    exit();
}

$conn->close();
?>