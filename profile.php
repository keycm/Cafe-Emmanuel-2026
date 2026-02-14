<?php
session_start();
require_once 'config.php';
require_once 'audit.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$userId = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Handle Profile Picture Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_picture'])) {
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_picture']['name'];
        $fileTmp = $_FILES['profile_picture']['tmp_name'];
        $fileSize = $_FILES['profile_picture']['size'];
        $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($fileExt, $allowed)) {
            $error_message = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        } elseif ($fileSize > 5000000) { // 5MB limit
            $error_message = "File size must be less than 5MB.";
        } else {
            // Create uploads directory if not exists
            $uploadDir = __DIR__ . '/uploads/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Generate unique filename
            $newFilename = 'user_' . $userId . '_' . time() . '.' . $fileExt;
            $destination = $uploadDir . $newFilename;
            
            if (move_uploaded_file($fileTmp, $destination)) {
                // Delete old profile picture if exists
                $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                $oldData = $result->fetch_assoc();
                $stmt->close();
                
                if ($oldData && $oldData['profile_picture'] && file_exists(__DIR__ . '/' . $oldData['profile_picture'])) {
                    @unlink(__DIR__ . '/' . $oldData['profile_picture']);
                }
                
                // Update database
                $relativePath = 'uploads/profiles/' . $newFilename;
                $updateStmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                $updateStmt->bind_param("si", $relativePath, $userId);
                
                if ($updateStmt->execute()) {
                    // Update Session immediately
                    $_SESSION['profile_pic'] = $relativePath;
                    $success_message = "Profile picture updated successfully!";
                    audit($userId, 'profile_picture_updated', 'users', $userId, ['filename' => $newFilename]);
                } else {
                    $error_message = "Database update failed.";
                }
                $updateStmt->close();
            } else {
                $error_message = "Failed to upload file.";
            }
        }
    } else {
        $error_message = "Please select a valid image file.";
    }
}

// Handle Profile Information Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($fullname) || empty($email)) {
        $error_message = "Full name and email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        // Check if email already exists for another user
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $checkStmt->bind_param("si", $email, $userId);
        $checkStmt->execute();
        $checkStmt->store_result();
        
        if ($checkStmt->num_rows > 0) {
            $error_message = "Email already in use by another account.";
        } else {
            $updateStmt = $conn->prepare("UPDATE users SET fullname = ?, email = ? WHERE id = ?");
            $updateStmt->bind_param("ssi", $fullname, $email, $userId);
            
            if ($updateStmt->execute()) {
                $_SESSION['fullname'] = $fullname;
                $_SESSION['email'] = $email;
                $success_message = "Profile information updated successfully!";
                audit($userId, 'profile_info_updated', 'users', $userId, ['fullname' => $fullname, 'email' => $email]);
            } else {
                $error_message = "Failed to update profile information.";
            }
            $updateStmt->close();
        }
        $checkStmt->close();
    }
}

// Handle Password Change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $error_message = "All password fields are required.";
    } elseif ($newPassword !== $confirmPassword) {
        $error_message = "New passwords do not match.";
    } elseif (!preg_match("/^(?=.*[A-Z]).{8,}$/", $newPassword)) {
        $error_message = "Password must be at least 8 characters with one capital letter.";
    } else {
        // Verify current password
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        if ($user && password_verify($currentPassword, $user['password'])) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $updateStmt->bind_param("si", $hashedPassword, $userId);
            
            if ($updateStmt->execute()) {
                $success_message = "Password changed successfully!";
                audit($userId, 'password_changed', 'users', $userId, []);
            } else {
                $error_message = "Failed to change password.";
            }
            $updateStmt->close();
        } else {
            $error_message = "Current password is incorrect.";
        }
    }
}

// Fetch current user data
$stmt = $conn->prepare("SELECT username, fullname, email, role, profile_picture, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo.png">
    <title>My Profile - Cafe Emmanuel</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Akaya+Telivigala&family=Archivo+Black&family=Archivo+Narrow:wght@400;700&family=Birthstone+Bounce:wght@500&family=Inknut+Antiqua:wght@600&family=Playfair+Display:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary-color: #B95A4B;
            --primary-dark: #9C4538;
            --secondary-color: #3C2A21;
            --text-color: #333;
            --heading-color: #1F1F1F;
            --white: #FFFFFF;
            --border-color: #EAEAEA;
            --bg-light: #FCFBF8;
            --footer-bg-color: #1a120b;
            --footer-text-color: #ccc;
            --footer-link-hover: #FFC94A;
            
            --font-logo-cafe: 'Archivo Black', sans-serif;
            --font-logo-emmanuel: 'Birthstone Bounce', cursive;
            --font-nav: 'Inknut Antiqua', serif;
            --font-body: 'Lato', sans-serif;
            
            --nav-height: 90px;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-body); padding-top: var(--nav-height); background-color: var(--bg-light); color: var(--text-color); }
        
        /* Header */
        .header { position: fixed; width: 100%; top: 0; z-index: 1000; height: var(--nav-height); background: rgba(26, 18, 11, 0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .navbar { display: flex; justify-content: space-between; align-items: center; height: 100%; max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .nav-logo { display: flex; align-items: center; color: var(--white); text-decoration: none; }
        .logo-cafe { font-family: var(--font-logo-cafe); font-size: 32px; letter-spacing: -1px; }
        .logo-emmanuel { font-family: var(--font-logo-emmanuel); font-size: 38px; margin-left: 8px; color: var(--primary-color); font-weight: 500; }
        
        .nav-menu { display: flex; gap: 2.5rem; list-style: none; }
        .nav-link { font-family: var(--font-nav); font-size: 15px; font-weight: 500; color: rgba(255,255,255,0.9); position: relative; text-decoration: none; }
        .nav-link::after { content: ''; position: absolute; width: 0; height: 2px; bottom: -4px; left: 0; background-color: var(--footer-link-hover); transition: width 0.3s; }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        .nav-link:hover, .nav-link.active { color: var(--footer-link-hover); }

        .nav-right-cluster { display: flex; align-items: center; gap: 1rem; }
        
        /* Icons & Avatar */
        .nav-icon-btn {
            position: relative; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;
            border-radius: 50%; background: rgba(255, 255, 255, 0.1); color: var(--white); font-size: 1.1rem; transition: all 0.3s ease; text-decoration: none;
        }
        .nav-icon-btn:hover { background: var(--footer-link-hover); color: var(--secondary-color); transform: translateY(-2px); }

        .user-avatar {
            width: 45px; height: 45px; border-radius: 50%; object-fit: cover;
            border: 2px solid var(--primary-color); transition: transform 0.3s ease; background: #fff;
        }
        .profile-dropdown:hover .user-avatar { transform: scale(1.05); box-shadow: 0 0 10px rgba(255, 255, 255, 0.3); }
        
        .profile-dropdown { position: relative; cursor: pointer; display: flex; align-items: center; }
        .profile-dropdown::after { content: ''; position: absolute; top: 100%; left: 0; width: 100%; height: 50px; background: transparent; }
        
        .profile-menu {
            display: none; position: absolute; right: 0; top: 140%; background: var(--white); min-width: 200px;
            border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); overflow: hidden; z-index: 1001;
        }
        .profile-dropdown:hover .profile-menu { display: block; }
        .profile-menu a { display: block; padding: 12px 20px; color: var(--text-color); font-size: 0.95rem; border-bottom: 1px solid var(--border-color); text-decoration: none; }
        .profile-menu a:hover { background: #f8f9fa; color: var(--primary-color); }

        .hamburger { display: none; cursor: pointer; }
        .bar { display: block; width: 25px; height: 3px; margin: 5px auto; background-color: var(--white); transition: 0.3s; }

        /* Main Container */
        .container { max-width: 900px; margin: 3rem auto; padding: 0 20px; }
        .back-button { display: inline-flex; align-items: center; gap: 8px; background: transparent; border: none; color: #666; font-size: 16px; cursor: pointer; padding: 10px 15px; margin-bottom: 20px; text-decoration: none; transition: background-color 0.3s; border-radius: 6px; }
        .back-button:hover { background: #e9ecef; color: #333; }
        .page-title { font-family: 'Playfair Display', serif; font-size: 2.5rem; color: var(--heading-color); margin-bottom: 2rem; }
        
        /* Alert Messages */
        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        /* Profile Card */
        .profile-card { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 2rem; border: 1px solid var(--border-color); }
        .profile-header { display: flex; align-items: center; gap: 2rem; padding-bottom: 2rem; border-bottom: 2px solid #eee; margin-bottom: 2rem; }
        .profile-picture { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid var(--primary-color); }
        .profile-picture-placeholder { width: 150px; height: 150px; border-radius: 50%; background: #e0e0e0; display: flex; align-items: center; justify-content: center; font-size: 4rem; color: #999; border: 4px solid #ddd; }
        .profile-details h2 { font-family: 'Playfair Display', serif; font-size: 2rem; margin-bottom: 0.5rem; }
        .profile-badge { display: inline-block; background: var(--primary-color); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem; }
        .profile-meta { color: #666; font-size: 0.95rem; line-height: 1.6; }
        
        /* Form Sections */
        .form-section { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 2rem; border: 1px solid var(--border-color); }
        .form-section h3 { font-family: 'Playfair Display', serif; font-size: 1.5rem; margin-bottom: 1.5rem; color: var(--heading-color); display: flex; align-items: center; gap: 10px; }
        .form-section h3 i { color: var(--primary-color); }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 0.5rem; color: #555; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem; transition: border-color 0.3s; }
        .form-group input:focus { outline: none; border-color: var(--primary-color); }
        
        .file-input-wrapper { position: relative; overflow: hidden; display: inline-block; }
        .file-input-label { display: inline-block; padding: 10px 20px; background: var(--primary-color); color: white; border-radius: 6px; cursor: pointer; font-weight: 600; transition: background 0.3s; }
        .file-input-label:hover { background: var(--primary-dark); }
        .file-input-wrapper input[type="file"] { position: absolute; left: -9999px; }
        .file-name { margin-left: 15px; color: #666; font-style: italic; }
        
        .btn { padding: 12px 30px; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-block; }
        .btn-primary { background: var(--primary-color); color: white; }
        .btn-primary:hover { background: var(--primary-dark); }

        @media (max-width: 768px) {
            .nav-menu { display: none; }
            .hamburger { display: block; }
            .profile-header { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>

<header class="header">
    <nav class="navbar">
        <a href="index.php" class="nav-logo">
            <span class="logo-cafe"><span class="first-letter">C</span>afe</span>
            <span class="logo-emmanuel"><span class="first-letter">E</span>mmanuel</span>
        </a>
        <ul class="nav-menu">
            <li><a href="index.php" class="nav-link">Home</a></li>
            <li><a href="product.php" class="nav-link">Menu</a></li>
            <li><a href="about.php" class="nav-link">About</a></li>
            <li><a href="contact.php" class="nav-link">Contact</a></li>
            <li><a href="my_orders.php" class="nav-link">My Orders</a></li>
        </ul>
        
        <div class="nav-right-cluster">
            <a href="cart.php" class="nav-icon-btn" title="View Cart"><i class="fas fa-shopping-cart"></i></a>
            
            <?php include 'notification_bell.php'; ?>

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
        </div>

        <div class="hamburger">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </nav>
</header>

<div class="container">
    <a href="<?php echo ($_SESSION['role'] === 'admin') ? 'Dashboard.php' : 'index.php'; ?>" class="back-button">
        <i class="fas fa-arrow-left"></i> Back
    </a>
    
    <h1 class="page-title">My Profile</h1>
    
    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
    
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-picture-container">
                <?php if ($user['profile_picture'] && file_exists(__DIR__ . '/' . $user['profile_picture'])): ?>
                    <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="profile-picture">
                <?php else: ?>
                    <div class="profile-picture-placeholder">
                        <i class="fa fa-user"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="profile-details">
                <span class="profile-badge"><?php echo ucfirst(htmlspecialchars($user['role'])); ?></span>
                <h2><?php echo htmlspecialchars($user['fullname']); ?></h2>
                <p class="profile-meta">
                    <i class="fa fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?><br>
                    <i class="fa fa-user"></i> @<?php echo htmlspecialchars($user['username']); ?><br>
                    <i class="fa fa-calendar"></i> Member since <?php echo date('F Y', strtotime($user['created_at'])); ?>
                </p>
            </div>
        </div>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label><i class="fa fa-camera"></i> Update Profile Picture</label>
                <div class="file-input-wrapper">
                    <label for="profile_picture" class="file-input-label">
                        <i class="fa fa-upload"></i> Choose Image
                    </label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" onchange="displayFileName(this)">
                    <span class="file-name" id="file-name">No file chosen</span>
                </div>
                <small style="display: block; margin-top: 8px; color: #666;">Max file size: 5MB. Allowed formats: JPG, PNG, GIF</small>
            </div>
            <button type="submit" name="upload_picture" class="btn btn-primary">
                <i class="fa fa-upload"></i> Upload Picture
            </button>
        </form>
    </div>
    
    <div class="form-section">
        <h3><i class="fa fa-edit"></i> Edit Profile Information</h3>
        <form method="POST">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" name="fullname" id="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Username (Cannot be changed)</label>
                <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled style="background:#f5f5f5; color:#888;">
            </div>
            <button type="submit" name="update_info" class="btn btn-primary">
                <i class="fa fa-save"></i> Save Changes
            </button>
        </form>
    </div>
    
    <div class="form-section">
        <h3><i class="fa fa-lock"></i> Change Password</h3>
        <form method="POST">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" id="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" id="new_password" required minlength="8">
                <small style="display: block; margin-top: 5px; color: #666;">Must be at least 8 characters with one capital letter</small>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit" name="change_password" class="btn btn-primary">
                <i class="fa fa-key"></i> Change Password
            </button>
        </form>
    </div>
</div>

<script>
    // Display selected file name
    function displayFileName(input) {
        const fileName = input.files[0] ? input.files[0].name : 'No file chosen';
        document.getElementById('file-name').textContent = fileName;
    }
    
    // Mobile Menu Toggle
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    if (hamburger) {
        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('active'); // You might need to add specific CSS for .active in media query
            if(navMenu.style.display === 'flex') {
                navMenu.style.display = 'none';
            } else {
                navMenu.style.display = 'flex';
                navMenu.style.flexDirection = 'column';
                navMenu.style.position = 'absolute';
                navMenu.style.top = '90px';
                navMenu.style.left = '0';
                navMenu.style.width = '100%';
                navMenu.style.backgroundColor = '#3C2A21';
                navMenu.style.padding = '20px';
                navMenu.style.textAlign = 'center';
            }
        });
    }
</script>

</body>
</html>