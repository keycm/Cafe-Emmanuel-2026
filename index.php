<?php
session_start();
include 'config.php'; 
require_once __DIR__ . '/audit.php';
require_once __DIR__ . '/mailer.php'; 

$login_error = '';
$register_error = '';
$register_success = '';
$otp_error = '';
$otp_success = '';

// ---------------------------------------------------------
// 1. OTP VERIFICATION LOGIC
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {
    $code_input = trim($_POST['otp_code'] ?? '');
    $userId = (int)($_SESSION['otp_user_id'] ?? 0);
    
    if (empty($code_input)) {
        $otp_error = 'Please enter the code.';
    } elseif (!$userId) {
        $otp_error = 'Session expired. Please login again.';
        unset($_SESSION['otp_user_id'], $_SESSION['otp_email'], $_SESSION['show_otp_modal']);
    } else {
        $stmt = $conn->prepare("SELECT id, code, expires_at, attempts FROM otp_codes WHERE user_id = ? AND used_at IS NULL ORDER BY id DESC LIMIT 1");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $otpRecord = $result->fetch_assoc();
        $stmt->close();
        
        if (!$otpRecord) {
            $otp_error = 'Code expired or not found. Please resend.';
        } elseif (time() > strtotime($otpRecord['expires_at'])) {
            $otp_error = 'Code expired. Please resend.';
        } elseif ((int)$otpRecord['attempts'] >= 5) {
            $otp_error = 'Too many attempts. Please request a new code.';
        } elseif ($code_input !== $otpRecord['code']) {
            $updateStmt = $conn->prepare("UPDATE otp_codes SET attempts = attempts + 1 WHERE id = ?");
            $updateStmt->bind_param('i', $otpRecord['id']);
            $updateStmt->execute();
            $updateStmt->close();
            $otp_error = 'Invalid code. Try again.';
        } else {
            $markStmt = $conn->prepare("UPDATE otp_codes SET used_at = NOW() WHERE id = ?");
            $markStmt->bind_param('i', $otpRecord['id']);
            $markStmt->execute();
            $markStmt->close();
            
            $verifyStmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
            if ($verifyStmt) {
                $verifyStmt->bind_param('i', $userId);
                $verifyStmt->execute();
                $verifyStmt->close();
            }
            
            // FIXED: Using profile_picture to match SQL schema
            $userStmt = $conn->prepare("SELECT fullname, role, profile_picture FROM users WHERE id = ?");
            $userStmt->bind_param('i', $userId);
            $userStmt->execute();
            $userRes = $userStmt->get_result();
            $userData = $userRes->fetch_assoc();
            $userStmt->close();

            $_SESSION['user_id'] = $userId;
            $_SESSION['email'] = $_SESSION['otp_email'];
            $_SESSION['fullname'] = $userData['fullname'] ?? $_SESSION['otp_fullname'];
            $_SESSION['role'] = $userData['role'] ?? $_SESSION['otp_role'];
            $_SESSION['profile_pic'] = $userData['profile_picture'] ?? null; 

            unset($_SESSION['otp_user_id'], $_SESSION['otp_email'], $_SESSION['otp_fullname'], $_SESSION['otp_role'], $_SESSION['show_otp_modal']);
            
            audit($userId, 'login_success_otp_verified', 'users', $userId, []);
            
            if (in_array($_SESSION['role'], ['admin', 'super_admin'])) {
                header("Location: Dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        }
    }
}

// ---------------------------------------------------------
// 2. LOGIN LOGIC
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $identifier = trim($_POST['identifier'] ?? $_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($identifier) || empty($password)) {
        $login_error = "Please fill in both fields.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $isVerified = !isset($user['is_verified']) || $user['is_verified'] == 1;

                if ($isVerified) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email']; 
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['profile_pic'] = $user['profile_picture'] ?? null; // FIXED: Changed from profile_pic
                    
                    audit($user['id'], 'login_success', 'users', $user['id'], []);
                    
                    if (in_array($user['role'], ['admin', 'super_admin'])) {
                        header("Location: Dashboard.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit;
                } else {
                    $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                    $expires = date('Y-m-d H:i:s', time() + 600);
                    
                    $otpStmt = $conn->prepare("INSERT INTO otp_codes (user_id, code, expires_at) VALUES (?, ?, ?)");
                    $otpStmt->bind_param("iss", $user['id'], $code, $expires);
                    $otpStmt->execute();
                    
                    $subject = 'Verify Your Account - Cafe Emmanuel';
                    $body = "<h3>Account Verification Needed</h3><p>Your code is: <b style='font-size:20px;'>$code</b></p>";
                    send_email($user['email'], $subject, $body);
                    
                    $_SESSION['otp_user_id'] = $user['id'];
                    $_SESSION['otp_email'] = $user['email'];
                    $_SESSION['otp_fullname'] = $user['fullname'];
                    $_SESSION['otp_role'] = $user['role'];
                    $_SESSION['show_otp_modal'] = true;
                    
                    header("Location: index.php?verify=pending");
                    exit;
                }
            } else {
                $login_error = "Wrong password!";
            }
        } else {
            $login_error = "Username or Email not found!";
        }
        $stmt->close();
    }
}

// ---------------------------------------------------------
// 3. REGISTRATION LOGIC
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $contact  = trim($_POST['contact'] ?? '');
    $gender   = trim($_POST['gender'] ?? '');
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        $register_error = "Passwords do not match!";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $register_error = "Email or Username already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO users (username, password, fullname, email, contact, gender, is_verified) VALUES (?, ?, ?, ?, ?, ?, 0)");
            $insert_stmt->bind_param("ssssss", $username, $hashed_password, $fullname, $email, $contact, $gender);

            if ($insert_stmt->execute()) {
                $new_user_id = $insert_stmt->insert_id;
                $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $expires = date('Y-m-d H:i:s', time() + 600);
                
                $otpStmt = $conn->prepare("INSERT INTO otp_codes (user_id, code, expires_at) VALUES (?, ?, ?)");
                $otpStmt->bind_param("iss", $new_user_id, $code, $expires);
                $otpStmt->execute();
                $otpStmt->close();
                
                $subject = "Welcome! Verify your Cafe Emmanuel Account";
                $body = "<div style='color:#333;'><h1>Welcome, $fullname!</h1><p>Verify your account using code: <b style='font-size:24px; color:#B95A4B;'>$code</b></p></div>";
                send_email($email, $subject, $body);

                $_SESSION['otp_user_id'] = $new_user_id;
                $_SESSION['otp_email'] = $email;
                $_SESSION['otp_fullname'] = $fullname;
                $_SESSION['otp_role'] = 'user';
                $_SESSION['show_otp_modal'] = true;

                header("Location: index.php?verify=new_account");
                exit;
            } else {
                $register_error = "Error: " . $insert_stmt->error;
            }
            $insert_stmt->close();
        }
        $stmt->close();
    }
}

// --- Fetch Menu Items (FIXED CATEGORIES TO MATCH SQL DUMP) ---
$coffee_items = $food_items = $sandwich_items = [];
if (isset($conn) && !$conn->connect_error) {
    // coffee -> over iced
    $coffee_result = $conn->query("SELECT * FROM products WHERE category = 'over iced' AND stock > 0 ORDER BY id DESC LIMIT 4");
    $coffee_items = $coffee_result ? $coffee_result->fetch_all(MYSQLI_ASSOC) : [];

    // pizza -> pizza
    $food_result = $conn->query("SELECT * FROM products WHERE category = 'pizza' AND stock > 0 ORDER BY id DESC LIMIT 4");
    $food_items = $food_result ? $food_result->fetch_all(MYSQLI_ASSOC) : [];

    // sandwich -> All Day Breakfast
    $sandwich_result = $conn->query("SELECT * FROM products WHERE category = 'All Day Breakfast' AND stock > 0 ORDER BY id DESC LIMIT 4");
    $sandwich_items = $sandwich_result ? $sandwich_result->fetch_all(MYSQLI_ASSOC) : [];
}

// =========================================================
// FETCH HERO SLIDES (VIDEO + IMAGES)
// =========================================================
$hero_slides = [];
$slides_sql = "SELECT * FROM hero_slides ORDER BY sort_order ASC";
$slides_res = $conn->query($slides_sql);

if ($slides_res && $slides_res->num_rows > 0) {
    $hero_slides = $slides_res->fetch_all(MYSQLI_ASSOC);
} else {
    $hero_sql = "SELECT * FROM hero_section LIMIT 1";
    $hero_result = $conn->query($hero_sql);
    $hero_data = ($hero_result) ? $hero_result->fetch_assoc() : null;

    if ($hero_data) {
        $hero_slides[] = [
            'type' => 'image',
            'file_path' => $hero_data['image_path'] ?? 'Cover-Photo.jpg',
            'heading' => $hero_data['heading'],
            'subtext' => $hero_data['subtext'],
            'button_text' => $hero_data['button_text'],
            'button_link' => $hero_data['button_link']
        ];
    } else {
        $hero_slides[] = [
            'type' => 'image',
            'file_path' => 'Cover-Photo.jpg',
            'heading' => 'Welcome to <span>Cafe</span>Emmanuel',
            'subtext' => 'Roasting with Art. Taste the difference.',
            'button_text' => 'View Menu',
            'button_link' => 'product.php'
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="logo.png">
    <title>Cafe Emmanuel - Roasting with Art</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Akaya+Telivigala&family=Archivo+Black&family=Archivo+Narrow:wght@400;700&family=Birthstone+Bounce:wght@500&family=Inknut+Antiqua:wght@600&family=Playfair+Display:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">

    <style>
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
            --font-hero: 'Akaya Telivigala', cursive;
            --font-heading: 'Playfair Display', serif;
            --font-body: 'Lato', sans-serif;
            --nav-height: 90px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; scroll-padding-top: var(--nav-height); }
        body { font-family: var(--font-body); color: var(--text-color); background-color: var(--bg-light); line-height: 1.7; overflow-x: hidden; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        a { text-decoration: none; transition: 0.3s; color: inherit; }
        ul { list-style: none; }
        .btn { display: inline-block; padding: 12px 30px; border-radius: 50px; font-weight: 700; font-size: 1rem; text-align: center; cursor: pointer; transition: all 0.3s ease; border: 2px solid transparent; font-family: var(--font-body); }
        .btn-primary { background-color: var(--primary-color); color: var(--white); box-shadow: 0 4px 15px rgba(185, 90, 75, 0.3); }
        .btn-primary:hover { background-color: var(--primary-dark); transform: translateY(-2px); }
        .btn-outline { background-color: transparent; color: var(--white); border-color: var(--white); }
        .btn-outline:hover { background-color: var(--white); color: var(--secondary-color); }
        .header { position: fixed; width: 100%; top: 0; z-index: 1000; height: var(--nav-height); background: transparent; transition: background 0.4s ease, box-shadow 0.4s ease; }
        .header.scrolled { background: rgba(26, 18, 11, 0.98); box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .navbar { display: flex; justify-content: space-between; align-items: center; height: 100%; }
        .nav-logo { display: flex; align-items: center; color: var(--white); }
        .logo-cafe { font-family: var(--font-logo-cafe); font-size: 32px; letter-spacing: -1px; }
        .logo-emmanuel { font-family: var(--font-logo-emmanuel); font-size: 38px; margin-left: 8px; color: var(--primary-color); font-weight: 500; }
        .nav-menu { display: flex; gap: 2.5rem; }
        .nav-link { font-family: var(--font-nav); font-size: 15px; font-weight: 500; color: rgba(255,255,255,0.9); position: relative; letter-spacing: 0.5px; }
        .nav-link::after { content: ''; position: absolute; width: 0; height: 2px; bottom: -4px; left: 0; background-color: var(--footer-link-hover); transition: width 0.3s; }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        .nav-link:hover, .nav-link.active { color: var(--footer-link-hover); }
        .nav-right-cluster { display: flex; align-items: center; gap: 1rem; }
        .nav-icon-btn { position: relative; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(255, 255, 255, 0.1); color: var(--white); font-size: 1.1rem; transition: all 0.3s ease; }
        .nav-icon-btn:hover { background: var(--footer-link-hover); color: var(--secondary-color); transform: translateY(-2px); }
        .user-avatar { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-color); transition: transform 0.3s ease; background: #fff; }
        .profile-dropdown:hover .user-avatar { transform: scale(1.05); box-shadow: 0 0 10px rgba(255, 255, 255, 0.3); }
        .login-trigger { background: var(--primary-color); color: var(--white); padding: 8px 24px; border-radius: 30px; font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; transition: background 0.3s; }
        .login-trigger:hover { background: var(--primary-dark); }
        .profile-dropdown { position: relative; cursor: pointer; display: flex; align-items: center;}
        .profile-dropdown::after { content: ''; position: absolute; top: 100%; left: 0; width: 100%; height: 50px; background: transparent; }
        .profile-menu { display: none; position: absolute; right: 0; top: 140%; background: var(--white); min-width: 200px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); overflow: hidden; z-index: 1001; }
        .profile-dropdown:hover .profile-menu { display: block; }
        .profile-menu a { display: block; padding: 12px 20px; color: var(--text-color); font-size: 0.95rem; border-bottom: 1px solid var(--border-color); }
        .profile-menu a:hover { background: #f8f9fa; color: var(--primary-color); }
        .hamburger { display: none; cursor: pointer; }
        .bar { display: block; width: 25px; height: 3px; margin: 5px auto; background-color: var(--white); transition: 0.3s; }
        .hero-section { position: relative; height: 100vh; width: 100%; overflow: hidden; display: flex; align-items: center; justify-content: flex-start; }
        .hero-slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-size: cover; background-position: center; opacity: 0; transition: opacity 1s ease-in-out; z-index: 0; }
        .hero-slide.active { opacity: 1; z-index: 1; }
        video.hero-slide { object-fit: cover; }
        .hero-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 60%, rgba(0,0,0,0) 100%); z-index: 2; pointer-events: none; }
        .hero-content-wrapper { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 3; display: flex; align-items: center; pointer-events: none; }
        .hero-text-item { display: none; max-width: 700px; padding-left: 5%; padding-right: 20px; text-align: left; pointer-events: auto; }
        .hero-text-item.active { display: block; animation: fadeInUp 1s ease-out forwards; }
        .hero-text-item h1 { font-family: var(--font-hero); font-size: 5rem; line-height: 1.1; margin-bottom: 1.5rem; color: #fff; text-shadow: 2px 2px 20px rgba(0,0,0,0.9); }
        .hero-text-item h1 span { color: var(--primary-color); }
        .hero-text-item p { font-size: 1.3rem; font-weight: 400; margin-bottom: 2.5rem; color: #eee; text-shadow: 1px 1px 10px rgba(0,0,0,0.9); max-width: 600px; }
        .hero-actions { display: flex; gap: 1rem; }
        .section-padding { padding: 6rem 0; }
        .section-title { font-family: var(--font-heading); font-size: 3rem; text-align: center; margin-bottom: 1rem; color: var(--heading-color); }
        .section-subtitle { text-align: center; color: #666; max-width: 600px; margin: 0 auto 3.5rem; font-size: 1.1rem; }
        .menu-tabs { display: flex; justify-content: center; gap: 1rem; margin-bottom: 3rem; }
        .tab-btn { background: transparent; border: 2px solid var(--border-color); padding: 10px 25px; border-radius: 30px; font-family: var(--font-body); font-weight: 600; color: #666; cursor: pointer; transition: all 0.3s; }
        .tab-btn:hover, .tab-btn.active { background: var(--primary-color); border-color: var(--primary-color); color: var(--white); }
        .menu-grid { display: none; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem; animation: fadeIn 0.5s ease; }
        .menu-grid.active { display: grid; }
        .menu-item-card { background: var(--white); border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: transform 0.3s ease; border: 1px solid var(--border-color); display: flex; flex-direction: column; cursor: pointer; }
        .menu-item-card:hover { transform: translateY(-8px); box-shadow: 0 15px 40px rgba(0,0,0,0.1); }
        .card-img { height: 220px; overflow: hidden; background: #f4f4f4; }
        .card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
        .menu-item-card:hover .card-img img { transform: scale(1.1); }
        .card-body { padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column; }
        .card-title { font-family: var(--font-heading); font-size: 1.3rem; font-weight: 700; margin-bottom: 0.5rem; }
        .card-desc { font-size: 0.9rem; color: #777; margin-bottom: 1rem; }
        .card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: auto; }
        .card-price { font-weight: 700; color: var(--primary-color); font-size: 1.2rem; }
        .about-section { background-color: var(--white); }
        .features-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-bottom: 4rem; }
        .feature-box { background: var(--bg-light); padding: 2.5rem; border-radius: 12px; text-align: center; border: 1px solid var(--border-color); }
        .feature-box i { font-size: 2.5rem; color: var(--primary-color); margin-bottom: 1.5rem; }
        .feature-box h4 { font-family: var(--font-heading); font-size: 1.25rem; margin-bottom: 10px; }
        .about-content { display: grid; grid-template-columns: 1.2fr 1fr; gap: 4rem; align-items: center; }
        .about-text h3 { font-family: var(--font-heading); font-size: 2.2rem; margin-bottom: 1.5rem; }
        .stats-row { display: flex; gap: 2rem; margin-top: 2rem; }
        .stat-item { text-align: center; flex: 1; background: rgba(185, 90, 75, 0.05); padding: 1rem; border-radius: 10px; }
        .stat-num { font-size: 2rem; font-weight: 700; color: var(--primary-color); display: block; }
        .stat-label { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: #777; }
        .contact-section { background-color: var(--bg-light); }
        .contact-wrapper { display: grid; grid-template-columns: 1fr 1.5fr; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.08); margin-bottom: 3rem; }
        .contact-details { background: var(--secondary-color); color: var(--white); padding: 3rem; display: flex; flex-direction: column; justify-content: center; }
        .contact-details h3 { color: var(--white); font-family: var(--font-heading); font-size: 2rem; margin-bottom: 2rem; }
        .contact-item { display: flex; gap: 1rem; margin-bottom: 1.5rem; }
        .contact-item i { color: var(--footer-link-hover); font-size: 1.2rem; margin-top: 5px; }
        .contact-item strong { display: block; font-size: 0.9rem; opacity: 0.7; margin-bottom: 3px; }
        .hours-box { background: rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 10px; margin-top: 1rem; }
        .hours-row { display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9rem; }
        .map-container { min-height: 400px; }
        .map-container iframe { width: 100%; height: 100%; border: 0; }
        .footer { background-color: var(--footer-bg-color); color: var(--footer-text-color); padding-top: 4rem; font-size: 0.95rem; }
        .footer-content { display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 3rem; padding-bottom: 3rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .footer-brand h3 { color: var(--white); font-family: var(--font-logo-cafe); font-size: 1.8rem; margin-bottom: 1rem; }
        .footer-brand p { opacity: 0.7; margin-bottom: 1.5rem; line-height: 1.7; }
        .socials { display: flex; gap: 15px; }
        .social-link { width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; transition: 0.3s; color: var(--white); }
        .social-link:hover { background: var(--footer-link-hover); color: var(--secondary-color); }
        .footer-col h4 { color: var(--white); font-size: 1.1rem; margin-bottom: 1.5rem; font-family: var(--font-body); font-weight: bold; }
        .footer-links li { margin-bottom: 0.8rem; }
        .footer-links a { color: rgba(255,255,255,0.6); transition: 0.3s; }
        .footer-links a:hover { color: var(--footer-link-hover); padding-left: 5px; }
        .copyright { text-align: center; padding: 1.5rem 0; opacity: 0.5; font-size: 0.85rem; }
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; align-items: center; justify-content: center; backdrop-filter: blur(5px); animation: fadeIn 0.3s; }
        .modal-box { background: #ffffff; padding: 40px; border-radius: 24px; width: 90%; max-width: 450px; position: relative; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        .modal-close { position: absolute; top: 20px; right: 25px; font-size: 1.5rem; color: #ccc; cursor: pointer; transition: 0.2s; }
        .modal-close:hover { color: var(--primary-color); }
        .modal-title { text-align: center; font-family: var(--font-heading); font-size: 2rem; margin-bottom: 2rem; color: var(--heading-color); }
        .input-group { position: relative; margin-bottom: 20px; }
        .input-icon { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 1rem; z-index: 2; }
        .input-field { width: 100%; padding: 14px 15px 14px 48px; border: 1px solid #e2e8f0; border-radius: 12px; font-family: var(--font-body); font-size: 1rem; background: #f8f9fa; transition: 0.3s; color: var(--text-color); }
        .input-field:focus { border-color: var(--primary-color); background: #fff; box-shadow: 0 0 0 4px rgba(185, 90, 75, 0.1); outline: none; }
        .modal-btn { width: 100%; padding: 14px; border-radius: 12px; font-size: 1rem; margin-top: 10px; border: none; cursor: pointer; background: var(--primary-color); color: white; font-weight: bold; }
        .modal-btn:hover { background: var(--primary-dark); }
        .modal-footer { text-align: center; margin-top: 25px; font-size: 0.95rem; color: #666; }
        .link-highlight { color: var(--primary-color); font-weight: 700; cursor: pointer; }
        .link-highlight:hover { text-decoration: underline; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); scale: 0.95; } to { opacity: 1; transform: translateY(0); scale: 1; } }
        @media (max-width: 992px) { .features-row { grid-template-columns: 1fr; } .about-content { grid-template-columns: 1fr; text-align: center; } .contact-wrapper { grid-template-columns: 1fr; } .hero-text-item h1 { font-size: 3.5rem; } .footer-content { grid-template-columns: 1fr; } }
        @media (max-width: 768px) { .nav-menu, .nav-right-cluster { display: none; } .hamburger { display: block; } .nav-menu.active { display: flex; flex-direction: column; position: absolute; top: var(--nav-height); left: 0; width: 100%; background: var(--secondary-color); padding: 2rem; text-align: center; } .mobile-link { display: block; margin-bottom: 1rem; } }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar container">
            <a href="#home" class="nav-logo">
                <span class="logo-cafe"><span class="first-letter">C</span>afe</span>
                <span class="logo-emmanuel"><span class="first-letter">E</span>mmanuel</span>
            </a>
            <ul class="nav-menu">
                <li><a href="#home" class="nav-link active">Home</a></li>
                <li><a href="product.php" class="nav-link">Menu</a></li>
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="contact.php" class="nav-link">Contact</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="my_orders.php" class="nav-link">My Orders</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="mobile-link" style="display:none;"><a href="logout.php" class="nav-link">Logout</a></li>
                <?php else: ?>
                    <li class="mobile-link" style="display:none;"><a href="#" onclick="openModal('loginModal')" class="nav-link">Login</a></li>
                <?php endif; ?>
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
                            <?php if (in_array($_SESSION['role'], ['admin', 'super_admin'])): ?>
                                <a href="Dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                            <?php endif; ?>
                            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
                        </div>
                    </div>
                <?php else: ?>
                    <button class="login-trigger" onclick="openModal('loginModal')">Login</button>
                <?php endif; ?>
            </div>
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </nav>
    </header>

    <section id="home" class="hero-section">
        <?php foreach ($hero_slides as $index => $slide): ?>
            <?php if ($slide['type'] === 'video'): ?>
                <video class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>" autoplay muted loop playsinline>
                    <source src="<?php echo htmlspecialchars($slide['file_path']); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php else: ?>
                <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>" 
                     style="background-image: url('<?php echo htmlspecialchars($slide['file_path']); ?>');">
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <div class="hero-overlay"></div>
        <div class="hero-content-wrapper container">
            <?php foreach ($hero_slides as $index => $slide): ?>
                <div class="hero-text-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <h1><?php echo $slide['heading']; ?></h1>
                    <p><?php echo nl2br(htmlspecialchars($slide['subtext'])); ?></p>
                    <div class="hero-actions">
                        <?php if (!empty($slide['button_text'])): ?>
                            <a href="<?php echo htmlspecialchars($slide['button_link']); ?>" class="btn btn-primary"><?php echo htmlspecialchars($slide['button_text']); ?></a>
                        <?php endif; ?>
                        <a href="https://mancavegallery.com/" class="btn btn-outline">View Our Gallery</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="menu" class="section-padding">
        <div class="container">
            <h2 class="section-title">Our Specialties</h2>
            <p class="section-subtitle">Handpicked favorites from our kitchen to your table.</p>
            <div class="menu-tabs">
                <button class="tab-btn active" onclick="switchTab('coffee', this)">Coffee</button>
                <button class="tab-btn" onclick="switchTab('food', this)">All Day Breakfast</button>
                <button class="tab-btn" onclick="switchTab('pizza', this)">Pizza</button>
            </div>

            <div id="coffee" class="menu-grid active">
                <?php if (!empty($coffee_items)): ?>
                    <?php foreach ($coffee_items as $item): ?>
                        <a href="product.php" class="menu-item-card">
                            <div class="card-img">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="card-desc">A rich and aromatic blend brewed to perfection.</p>
                                <div class="card-footer">
                                    <span class="card-price">₱<?php echo number_format($item['price'], 2); ?></span>
                                    <span class="btn-primary" style="padding: 5px 12px; border-radius: 50%; font-size: 0.8rem;"><i class="fas fa-plus"></i></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; grid-column: 1/-1;">No items found.</p>
                <?php endif; ?>
            </div>

            <div id="food" class="menu-grid">
                <?php if (!empty($food_items)): ?>
                    <?php foreach ($food_items as $item): ?>
                        <a href="product.php" class="menu-item-card">
                            <div class="card-img">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="card-desc">Freshly prepared <?php echo htmlspecialchars($item['category']); ?>.</p>
                                <div class="card-footer">
                                    <span class="card-price">₱<?php echo number_format($item['price'], 2); ?></span>
                                    <span class="btn-primary" style="padding: 5px 12px; border-radius: 50%; font-size: 0.8rem;"><i class="fas fa-plus"></i></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div id="pizza" class="menu-grid">
                <?php if (!empty($sandwich_items)): ?>
                    <?php foreach ($sandwich_items as $item): ?>
                        <a href="product.php" class="menu-item-card">
                            <div class="card-img">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="card-desc">Sweet treats to brighten your day.</p>
                                <div class="card-footer">
                                    <span class="card-price">₱<?php echo number_format($item['price'], 2); ?></span>
                                    <span class="btn-primary" style="padding: 5px 12px; border-radius: 50%; font-size: 0.8rem;"><i class="fas fa-plus"></i></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div style="text-align: center; margin-top: 3rem;">
                <a href="product.php" class="btn btn-outline" style="border-color: var(--primary-color); color: var(--primary-color);">Explore Full Menu</a>
            </div>
        </div>
    </section>

    <section id="about" class="section-padding about-section">
        <div class="container">
            <div class="features-row">
                <div class="feature-box">
                    <i class="fas fa-mug-hot"></i>
                    <h4>Artisanal Coffee</h4>
                    <p style="color: #666;">Sourced from the best farms and roasted in-house.</p>
                </div>
                <div class="feature-box">
                    <i class="fas fa-heart"></i>
                    <h4>Made with Love</h4>
                    <p style="color: #666;">Prepared fresh daily using premium ingredients.</p>
                </div>
                <div class="feature-box">
                    <i class="fas fa-users"></i>
                    <h4>Community Hub</h4>
                    <p style="color: #666;">A place where neighbors become family.</p>
                </div>
            </div>
            <div class="about-content">
                <div class="about-text">
                    <h4 style="color:var(--primary-color); text-transform:uppercase; letter-spacing:1px; font-size:0.9rem;">Our Story</h4>
                    <h3>A Tradition of Excellence</h3>
                    <p style="color:#555; font-size:1.05rem;">What started as a simple dream to serve exceptional coffee has grown into a community cornerstone. We wanted to create a space that felt like an extension of your living room.</p>
                    <div class="stats-row">
                        <div class="stat-item"><span class="stat-num">500+</span><span class="stat-label">Daily Cups</span></div>
                        <div class="stat-item"><span class="stat-num">6</span><span class="stat-label">Years</span></div>
                        <div class="stat-item"><span class="stat-num">7</span><span class="stat-label">Days Open</span></div>
                    </div>
                </div>
                <div class="about-image">
                    <img src="Cover-Photo.jpg" alt="Cafe Interior" style="width:100%; border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,0.1);">
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="section-padding contact-section">
        <div class="container">
            <div class="contact-wrapper">
                <div class="contact-details">
                    <h3>Visit Us</h3>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Location</strong>
                            <p>San Antonio Road, Purok Dayat, San Antonio, Guagua, Pampanga, Philippines</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>Phone</strong>
                            <p>0995 100 9209</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>Email</strong>
                            <p>emmanuel.cafegallery@gmail.com</p>
                        </div>
                    </div>
                    <div class="hours-box">
                        <h4 style="color:white; margin-bottom:15px; border-bottom:1px solid rgba(255,255,255,0.2); padding-bottom:10px;">Opening Hours</h4>
                        <div class="hours-row"><span>Monday - Thursday</span> <span>10:00 AM - 11:00 PM</span></div>
                        <div class="hours-row"><span>Friday - Sunday</span> <span>10:00 AM - 12:00 MN</span></div>
                    </div>
                </div>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3851.6441551049283!2d120.61334867490074!3d14.973415985558917!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x339659f8a002829d%3A0xc3f1f3e070d6556e!2sCafe%20Emmanuel!5e0!3m2!1sen!2sph!4v1709400000000!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>

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
                        <li><a href="#home">Home</a></li>
                        <li><a href="product.php">Menu</a></li>
                        <li><a href="#about">About Us</a></li>
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

    <div id="loginModal" class="modal-overlay" <?php if ($login_error) echo 'style="display:flex;"'; ?>>
        <div class="modal-box">
            <span class="modal-close" onclick="closeModal('loginModal')">×</span>
            <h2 class="modal-title">Welcome Back</h2>
            <?php if ($login_error): ?><p style="color: #dc3545; text-align: center; margin-bottom: 15px; font-size: 0.9rem;"><?php echo $login_error; ?></p><?php endif; ?>
            <form method="POST" action="index.php">
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="text" name="identifier" placeholder="Email or Username" class="input-field" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" placeholder="Password" class="input-field" required>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:20px; font-size:0.9rem; color:#666;">
                    <label><input type="checkbox" name="remember"> Remember me</label>
                    <a href="#" onclick="switchModal('loginModal', 'forgotPasswordModal')" class="link-highlight">Forgot Password?</a>
                </div>
                <button type="submit" name="login" class="modal-btn">Login</button>
            </form>
            <div class="modal-footer">
                Don't have an account? <a href="#" onclick="switchModal('loginModal', 'registerModal')" class="link-highlight">Register</a>
            </div>
        </div>
    </div>

    <div id="registerModal" class="modal-overlay" <?php if ($register_error) echo 'style="display:flex;"'; ?>>
        <div class="modal-box">
            <span class="modal-close" onclick="closeModal('registerModal')">×</span>
            <h2 class="modal-title">Create Account</h2>
            <?php if ($register_error): ?><p style="color: #dc3545; text-align: center; margin-bottom: 10px; font-size: 0.9rem;"><?php echo $register_error; ?></p><?php endif; ?>
            <form method="POST" action="index.php">
                <div class="input-group">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="fullname" placeholder="Full Name" class="input-field" required minlength="8">
                </div>
                <div class="input-group">
                    <i class="fas fa-at input-icon"></i>
                    <input type="text" name="username" placeholder="Username" class="input-field" required minlength="4">
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" placeholder="Email Address" class="input-field" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-phone input-icon"></i>
                    <input type="tel" name="contact" placeholder="Contact Number" class="input-field" pattern="[0-9]{10,15}">
                </div>
                <div class="input-group">
                    <i class="fas fa-venus-mars input-icon"></i>
                    <select name="gender" class="input-field" required style="-webkit-appearance: none;">
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Non-Binary">Non-Binary</option>
                        <option value="Prefer not to say">Prefer not to say</option>
                    </select>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" placeholder="Password (Min 8 chars, 1 Uppercase)" class="input-field" required minlength="8" pattern="^(?=.*[A-Z]).{8,}$">
                </div>
                <div class="input-group">
                    <i class="fas fa-check-circle input-icon"></i>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" class="input-field" required>
                </div>
                <button type="submit" name="register" class="modal-btn">Register</button>
            </form>
            <div class="modal-footer">
                Already have an account? <a href="#" onclick="switchModal('registerModal', 'loginModal')" class="link-highlight">Login</a>
            </div>
        </div>
    </div>

    <div id="otpModal" class="modal-overlay" <?php if(isset($_SESSION['show_otp_modal']) && $_SESSION['show_otp_modal']): ?>style="display:flex;"<?php endif; ?>>
        <div class="modal-box">
            <span class="modal-close" onclick="window.location.href='logout.php'">×</span>
            <h2 class="modal-title">Verify Account</h2>
            <p style="text-align: center; color: #666; margin-bottom: 1.5rem;">
                We've sent a 6-digit code to <br><strong><?php echo htmlspecialchars($_SESSION['otp_email'] ?? ''); ?></strong>
            </p>
            <?php if (isset($_SESSION['otp_resent']) && $_SESSION['otp_resent']): unset($_SESSION['otp_resent']); ?>
                <p style="color: #28a745; text-align: center; margin-bottom: 10px;">New code sent!</p>
            <?php endif; ?>
            <?php if ($otp_error): ?><p style="color: #dc3545; text-align: center; margin-bottom: 10px;"><?php echo $otp_error; ?></p><?php endif; ?>
            <form method="POST" action="index.php">
                <div class="input-group">
                    <i class="fas fa-key input-icon"></i>
                    <input type="text" name="otp_code" placeholder="000000" class="input-field" required maxlength="6" pattern="[0-9]{6}" style="text-align: center; letter-spacing: 8px; font-size: 1.5rem; font-weight: 700;">
                </div>
                <button type="submit" name="verify_otp" class="modal-btn">Verify Code</button>
            </form>
            <div style="text-align: center; margin-top: 20px; display: flex; justify-content: space-between; font-size: 0.9rem;">
                <a href="resend_otp.php" class="link-highlight">Resend Code</a>
                <a href="logout.php" style="color: #999;">Use different account</a>
            </div>
        </div>
    </div>

    <div id="forgotPasswordModal" class="modal-overlay">
        <div class="modal-box">
            <span class="modal-close" onclick="closeModal('forgotPasswordModal')">×</span>
            <h2 class="modal-title">Reset Password</h2>
            <p style="text-align:center; color:#666; margin-bottom:20px;">Enter your email to receive a reset code.</p>
            <div id="forgotPasswordMessage" style="display:none; padding:10px; border-radius:6px; margin-bottom:15px; font-size:0.9rem; text-align:center;"></div>
            <form id="forgotPasswordForm">
                <input type="hidden" name="resetMethod" value="email">
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="forgotEmail" placeholder="Enter your email address" class="input-field" required>
                </div>
                <button type="submit" class="modal-btn">Send Reset Code</button>
            </form>
            <div class="modal-footer">
                Remember your password? <a href="#" onclick="switchModal('forgotPasswordModal', 'loginModal')" class="link-highlight">Login</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const slides = document.querySelectorAll(".hero-slide");
            const textItems = document.querySelectorAll(".hero-text-item");
            let currentSlide = 0;
            const slideInterval = 6000;

            function showSlide(index) {
                slides.forEach(s => s.classList.remove("active"));
                textItems.forEach(t => t.classList.remove("active"));
                slides[index].classList.add("active");
                textItems[index].classList.add("active");
                const currentElement = slides[index];
                if (currentElement.tagName === 'VIDEO') {
                    currentElement.currentTime = 0;
                    currentElement.play();
                }
            }
            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }
            if (slides.length > 1) {
                setInterval(nextSlide, slideInterval);
            }
        });

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        function switchModal(fromId, toId) {
            closeModal(fromId);
            openModal(toId);
        }
        function switchTab(tabId, btn) {
            document.querySelectorAll('.menu-grid').forEach(grid => grid.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            btn.classList.add('active');
        }

        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('.nav-menu');
        const mobileLinks = document.querySelectorAll('.mobile-link');
        if(hamburger) {
            hamburger.addEventListener('click', () => {
                navMenu.classList.toggle('active');
                mobileLinks.forEach(link => {
                    link.style.display = navMenu.classList.contains('active') ? 'block' : 'none';
                });
            });
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.style.display = "none";
            }
        }
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        document.getElementById('forgotPasswordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = document.getElementById('forgotEmail').value;
            const msgBox = document.getElementById('forgotPasswordMessage');
            msgBox.style.display = 'block';
            msgBox.style.background = '#e2e8f0';
            msgBox.style.color = '#333';
            msgBox.textContent = 'Sending reset code...';
            try {
                const formData = new FormData();
                formData.append('ajax', '1');
                formData.append('send_reset_code', '1');
                formData.append('identifier', email);
                formData.append('reset_method', 'email');
                const response = await fetch('forgot_password.php', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.success) {
                    msgBox.style.background = '#d4edda';
                    msgBox.style.color = '#155724';
                    msgBox.textContent = data.message + ' Redirecting...';
                    setTimeout(() => { window.location.href = 'reset_password.php'; }, 2000);
                } else {
                    msgBox.style.background = '#f8d7da';
                    msgBox.style.color = '#721c24';
                    msgBox.textContent = data.message;
                }
            } catch (error) {
                msgBox.textContent = 'An error occurred. Please try again.';
            }
        });
    </script>
</body>
</html>