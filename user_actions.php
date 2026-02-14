<?php
session_start();

// 1. Enable Error Reporting (Helps debug if there is still an issue)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Connect to Database using your existing file
// Make sure your db_connect.php file has the content you just uploaded!
require_once 'db_connect.php'; 

// 3. Include Audit Log
// We use require_once to ensure the functions are available
if (file_exists('audit_log.php')) {
    require_once 'audit_log.php';
}

// Check connection validity
if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . ($conn->connect_error ?? "Database variable not set"));
}

// Ensure the current user is an admin before proceeding
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'super_admin'])) {
    die("Access denied.");
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $user_id = intval($_GET['id']);
    $admin_id = $_SESSION['user_id'];
    $admin_name = $_SESSION['fullname'];

    // Security check: an admin cannot demote or delete their own account
    if ($user_id == $admin_id) {
        header("Location: user_accounts.php?error=cannot_modify_self");
        exit();
    }

    // Begin Transaction to ensure data integrity
    $conn->begin_transaction();
    
    try {
        if ($action === 'promote') {
            // Only super_admin can promote
            if ($_SESSION['role'] === 'super_admin') {
                $stmt = $conn->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->close();
                
                if (function_exists('logAdminAction')) {
                    logAdminAction($conn, $admin_id, $admin_name, 'user_role_change', "Promoted user ID #{$user_id} to admin", 'users', $user_id);
                }
            }
        } elseif ($action === 'demote') {
            // Only super_admin can demote
            if ($_SESSION['role'] === 'super_admin') {
                $stmt = $conn->prepare("UPDATE users SET role = 'user' WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->close();
                
                if (function_exists('logAdminAction')) {
                    logAdminAction($conn, $admin_id, $admin_name, 'user_role_change', "Demoted user ID #{$user_id} to user", 'users', $user_id);
                }
            }
        } elseif ($action === 'delete') {
            // 1. Get the user's data
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            if ($user) {
                // Security check: Admin cannot delete super_admin
                if ($user['role'] === 'super_admin' && $_SESSION['role'] !== 'super_admin') {
                     throw new Exception("Admin cannot delete Super Admin.");
                }

                // 2. Insert into the recently_deleted_users table (Recycle Bin)
                // Note: We use NOW() for the deleted_at timestamp
                $insert_stmt = $conn->prepare("INSERT INTO recently_deleted_users (fullname, username, email, role, deleted_at) VALUES (?, ?, ?, ?, NOW())");
                if (!$insert_stmt) {
                    // Create table if it doesn't exist (Backup plan)
                    $conn->query("CREATE TABLE IF NOT EXISTS recently_deleted_users (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        fullname VARCHAR(255),
                        username VARCHAR(255),
                        email VARCHAR(255),
                        role VARCHAR(50),
                        deleted_at DATETIME
                    )");
                    $insert_stmt = $conn->prepare("INSERT INTO recently_deleted_users (fullname, username, email, role, deleted_at) VALUES (?, ?, ?, ?, NOW())");
                }
                
                $insert_stmt->bind_param("ssss", $user['fullname'], $user['username'], $user['email'], $user['role']);
                if (!$insert_stmt->execute()) {
                    throw new Exception("Failed to backup user to recycle bin: " . $insert_stmt->error);
                }
                $insert_stmt->close();

                // 3. Delete from the main users table
                $delete_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
                $delete_stmt->bind_param("i", $user_id);
                if (!$delete_stmt->execute()) {
                    throw new Exception("Failed to delete user.");
                }
                $delete_stmt->close();

                // Log the action
                if (function_exists('logAdminAction')) {
                    logAdminAction(
                        $conn,
                        $admin_id,
                        $admin_name,
                        'user_delete',
                        "Moved user to recycle bin: {$user['username']} (ID: {$user_id})",
                        'users',
                        $user_id
                    );
                }
            } else {
                throw new Exception("User not found.");
            }
        }
        
        $conn->commit();
        header("Location: user_accounts.php?success=user_deleted");

    } catch (Exception $e) {
        $conn->rollback();
        // Log the error
        if (function_exists('logAdminAction')) {
            logAdminAction(
                $conn,
                $admin_id,
                $admin_name,
                'user_action_failed',
                "Failed action '{$action}' on user ID {$user_id}. Error: {$e->getMessage()}",
                'users',
                $user_id
            );
        }
        // Redirect with specific error message
        header("Location: user_accounts.php?error=" . urlencode($e->getMessage()));
    }
} else {
    // Redirect if no action
    header("Location: user_accounts.php");
}
exit();
?>