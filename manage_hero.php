<?php
// --- 1. ENABLE ERROR REPORTING ---
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'session_check.php';
require_once 'audit_log.php'; 
include 'db_connect.php';

// --- 2. CHECK CONNECTION ---
if (!isset($conn) || $conn->connect_error) {
    die("❌ Connection failed: " . ($conn->connect_error ?? "Database variable missing"));
}

// --- 3. AUTO-FIX: Create 'hero_slides' Table if missing ---
$tableCheck = $conn->query("SHOW TABLES LIKE 'hero_slides'");
if ($tableCheck->num_rows == 0) {
    $sql = "CREATE TABLE hero_slides (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        file_path VARCHAR(255) NOT NULL,
        type ENUM('image', 'video') NOT NULL DEFAULT 'image',
        heading VARCHAR(255),
        subtext TEXT,
        button_text VARCHAR(50) DEFAULT 'View Menu',
        button_link VARCHAR(255) DEFAULT 'product.php',
        sort_order INT(11) DEFAULT 0
    )";
    $conn->query($sql);
    
    // Seed with default data if empty
    $conn->query("INSERT INTO hero_slides (file_path, type, heading, subtext) VALUES 
        ('uploads/hero_default.png', 'image', 'Welcome to Cafe Emmanuel', 'Roasting with Art. Taste the difference.')");
}

// --- DATA FOR HEADER ICONS ---
$inquiry_count_result = $conn->query("SELECT COUNT(*) as count FROM inquiries WHERE status = 'new'");
$unread_inquiries = $inquiry_count_result ? $inquiry_count_result->fetch_assoc()['count'] : 0;

$recent_inquiries_result = $conn->query("SELECT * FROM inquiries WHERE status = 'new' ORDER BY received_at DESC LIMIT 5");
$recent_messages = [];
if ($recent_inquiries_result) {
    while ($row = $recent_inquiries_result->fetch_assoc()) {
        $recent_messages[] = $row;
    }
}

$successMessage = "";
$errorMessage = "";

// --- 4. HANDLE DELETE SLIDE ---
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM hero_slides WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $successMessage = "✅ Slide deleted successfully.";
        if(function_exists('logAdminAction')) {
            logAdminAction($conn, $_SESSION['user_id'] ?? 0, $_SESSION['fullname'] ?? 'Admin', 'delete_slide', "Deleted hero slide ID: $id", 'hero_slides', $id);
        }
    }
    $stmt->close();
}

// --- 5. HANDLE ADD / UPDATE SLIDE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_slide'])) {
    $slide_id = $_POST['slide_id'] ?? null;
    $heading = $_POST['heading'];
    $subtext = $_POST['subtext'];
    $btn_text = $_POST['button_text'];
    $btn_link = $_POST['button_link'];
    $sort_order = (int)$_POST['sort_order'];
    $type = 'image'; 
    $file_path = $_POST['current_file'] ?? '';

    if (isset($_FILES['slide_file']) && $_FILES['slide_file']['error'] === 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $file_name = time() . "_" . basename($_FILES["slide_file"]["name"]);
        $target_file = $target_dir . $file_name;
        $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        $allowed_imgs = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowed_vids = ['mp4', 'webm', 'ogg'];
        
        if (in_array($ext, $allowed_imgs)) { $type = 'image'; }
        elseif (in_array($ext, $allowed_vids)) { $type = 'video'; }
        else { $errorMessage = "❌ Invalid file type. Only JPG, PNG, MP4 allowed."; }

        if (empty($errorMessage)) {
            if (move_uploaded_file($_FILES["slide_file"]["tmp_name"], $target_file)) { $file_path = $target_file; }
            else { $errorMessage = "❌ Upload failed. Check permissions."; }
        }
    }

    if (empty($errorMessage)) {
        if ($slide_id) {
            $stmt = $conn->prepare("UPDATE hero_slides SET heading=?, subtext=?, button_text=?, button_link=?, sort_order=?, file_path=?, type=? WHERE id=?");
            $stmt->bind_param("ssssissi", $heading, $subtext, $btn_text, $btn_link, $sort_order, $file_path, $type, $slide_id);
            $action = "Updated Slide";
        } else {
            if (empty($file_path)) { $errorMessage = "❌ You must upload a file for a new slide."; }
            else {
                $stmt = $conn->prepare("INSERT INTO hero_slides (heading, subtext, button_text, button_link, sort_order, file_path, type) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssiss", $heading, $subtext, $btn_text, $btn_link, $sort_order, $file_path, $type);
                $action = "Added New Slide";
            }
        }

        if (empty($errorMessage) && isset($stmt)) {
            if ($stmt->execute()) {
                $successMessage = "✅ $action successfully!";
                if(function_exists('logAdminAction')) {
                    logAdminAction($conn, $_SESSION['user_id'] ?? 0, $_SESSION['fullname'] ?? 'Admin', 'manage_slide', $action, 'hero_slides', $slide_id ?? $conn->insert_id);
                }
            } else { $errorMessage = "❌ Database Error: " . $stmt->error; }
            $stmt->close();
        }
    }
}

// --- 6. FETCH ALL SLIDES ---
$slides = [];
$result = $conn->query("SELECT * FROM hero_slides ORDER BY sort_order ASC");
if ($result) { $slides = $result->fetch_all(MYSQLI_ASSOC); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Hero Slides - Cafe Emmanuel</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-red: #E03A3E;
        --secondary-dark: #222222;
        --text-muted: #777777;
        --bg-light: #f8f9fa;
        --card-bg: #FFFFFF;
        --border-color: #eeeeee;
        --font-main: 'Poppins', sans-serif;
        --font-heading: 'Montserrat', sans-serif;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: var(--font-main);
        background-color: var(--bg-light);
        color: var(--secondary-dark);
        display: flex;
        height: 100vh;
        overflow: hidden;
    }

    .main-content { 
        flex-grow: 1;
        margin-left: 260px;
        width: calc(100% - 260px); 
        height: 100vh;
        overflow-y: auto;
        padding: 40px;
    }

    .main-header { 
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
    }
    
    .main-header h1 { 
        font-family: var(--font-heading);
        font-size: 28px; 
        font-weight: 700; 
    }

    /* --- HEADER ICONS --- */
    .header-icons { display: flex; align-items: center; gap: 20px; }
    .icon-container {
        position: relative;
        cursor: pointer;
        width: 40px; height: 40px;
        background: white;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--border-color);
        transition: 0.3s;
    }
    .icon-container:hover { background: var(--primary-red); color: white; border-color: var(--primary-red); }
    .header-badge {
        position: absolute;
        top: -5px; right: -5px;
        background-color: var(--primary-red); color: white;
        border-radius: 50%;
        width: 18px; height: 18px;
        font-size: 10px;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; border: 2px solid #fff;
    }
    .header-icons img { width: 40px; height: 40px; border-radius: 50%; border: 1px solid var(--border-color); }

    /* Layout */
    .admin-grid { display: grid; grid-template-columns: 350px 1fr; gap: 30px; align-items: start; }
    
    /* Form Card */
    .card { background: var(--card-bg); border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid var(--border-color); }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: var(--text-muted); font-size: 0.85rem; }
    .form-control { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.9rem; font-family: var(--font-main); outline: none; }
    .form-control:focus { border-color: var(--primary-red); }
    
    .btn-submit { background-color: var(--primary-red); color: white; padding: 12px; border: none; border-radius: 6px; width: 100%; cursor: pointer; font-weight: 600; transition: 0.2s; }
    .btn-submit:hover { background-color: #c02d31; }

    /* Slides List */
    .slides-container { display: grid; gap: 20px; }
    .slide-item { background: white; border-radius: 12px; padding: 20px; display: flex; gap: 20px; align-items: center; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    
    .slide-preview { width: 150px; height: 100px; border-radius: 8px; overflow: hidden; background: #f0f0f0; flex-shrink: 0; position: relative; }
    .slide-preview img, .slide-preview video { width: 100%; height: 100%; object-fit: cover; }
    .slide-badge { position: absolute; top: 5px; left: 5px; background: rgba(0,0,0,0.7); color: white; padding: 2px 8px; font-size: 0.65rem; border-radius: 4px; text-transform: uppercase; }
    
    .slide-info { flex-grow: 1; }
    .slide-info h4 { margin: 0 0 5px 0; font-family: var(--font-heading); color: var(--secondary-dark); }
    .slide-info p { margin: 0 0 10px 0; color: var(--text-muted); font-size: 0.85rem; }
    
    .slide-actions { display: flex; gap: 8px; }
    .action-btn { 
        width: 32px; height: 32px; border-radius: 6px; border: 1px solid var(--border-color);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: 0.2s; background: white; color: var(--text-muted); text-decoration: none;
    }
    .action-btn:hover { background: var(--primary-red); color: white; border-color: var(--primary-red); }

    /* Dropdowns */
    .dropdown-menu {
        display: none;
        position: absolute;
        right: 0; top: 50px;
        background: white;
        width: 300px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-radius: 8px;
        z-index: 1001;
        border: 1px solid var(--border-color);
    }
    .dropdown-menu.show { display: block; }
    .dropdown-item { padding: 12px 15px; display: block; text-decoration: none; color: var(--secondary-dark); font-size: 13px; border-bottom: 1px solid var(--border-color); }
    .dropdown-item:hover { background: var(--bg-light); color: var(--primary-red); }

    .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; }
    .alert-success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .alert-error { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }

    @media (max-width: 1024px) { .main-content { margin-left: 0; width: 100%; } .admin-grid { grid-template-columns: 1fr; } }
</style>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <main class="main-content">
        <header class="main-header">
            <h1>Manage Hero Slides</h1>
            <div class="header-icons">
                <div class="icon-container" id="messageIcon">
                    <i class="fas fa-envelope"></i>
                    <?php if($unread_inquiries > 0): ?><span class="header-badge"><?php echo $unread_inquiries; ?></span><?php endif; ?>
                    <div class="dropdown-menu" id="messageDropdown">
                        <?php foreach ($recent_messages as $msg): ?>
                            <a href="admin_inquiries.php" class="dropdown-item">
                                <strong><?php echo htmlspecialchars($msg['first_name']); ?></strong>: <?php echo htmlspecialchars(substr($msg['message'], 0, 30)); ?>...
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="icon-container" id="notificationBell"><i class="fas fa-bell"></i></div>
                <div class="icon-container" id="profileIcon" style="border:none;">
                    <img src="logo.png" alt="Admin">
                    <div class="dropdown-menu" id="profileDropdown" style="width:150px;">
                        <a href="logout.php" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
        </header>

        <?php if($successMessage): ?><div class="alert alert-success"><?php echo $successMessage; ?></div><?php endif; ?>
        <?php if($errorMessage): ?><div class="alert alert-error"><?php echo $errorMessage; ?></div><?php endif; ?>

        <div class="admin-grid">
            <div class="card">
                <h3 id="formTitle" style="margin-bottom:20px; font-family:var(--font-heading); color:var(--primary-red);">Add New Slide</h3>
                <form method="POST" enctype="multipart/form-data" id="slideForm">
                    <input type="hidden" name="slide_id" id="slide_id">
                    <input type="hidden" name="current_file" id="current_file">
                    
                    <div class="form-group">
                        <label>Media File</label>
                        <input type="file" name="slide_file" class="form-control" accept="image/*,video/mp4,video/webm">
                    </div>

                    <div class="form-group">
                        <label>Heading</label>
                        <input type="text" name="heading" id="heading" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Subtext</label>
                        <textarea name="subtext" id="subtext" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Button Text</label>
                        <input type="text" name="button_text" id="button_text" class="form-control" value="View Menu">
                    </div>

                    <div class="form-group">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control" value="1">
                    </div>

                    <button type="submit" name="save_slide" class="btn-submit">Save Slide</button>
                    <button type="button" onclick="resetForm()" style="width:100%; margin-top:10px; background:white; border:1px solid var(--border-color); padding:10px; border-radius:6px; cursor:pointer; font-size:12px;">Cancel</button>
                </form>
            </div>

            <div class="slides-container">
                <?php if (empty($slides)): ?>
                    <div style="text-align:center; padding:50px; background:white; border-radius:12px; border:1px solid var(--border-color); color:var(--text-muted);">
                        <i class="fas fa-images fa-3x" style="margin-bottom:15px; opacity:0.2;"></i>
                        <p>No slides found.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($slides as $slide): ?>
                        <div class="slide-item">
                            <div class="slide-preview">
                                <?php if($slide['type'] == 'video'): ?>
                                    <video src="<?php echo htmlspecialchars($slide['file_path']); ?>" muted></video>
                                    <span class="slide-badge"><i class="fas fa-video"></i> Vid</span>
                                <?php else: ?>
                                    <img src="<?php echo htmlspecialchars($slide['file_path']); ?>" alt="Slide">
                                    <span class="slide-badge"><i class="fas fa-image"></i> Img</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="slide-info">
                                <h4><?php echo htmlspecialchars($slide['heading']); ?></h4>
                                <p><?php echo htmlspecialchars($slide['subtext']); ?></p>
                                <small style="color:var(--text-muted); font-size:10px;">Order: <?php echo $slide['sort_order']; ?></small>
                            </div>

                            <div class="slide-actions">
                                <button type="button" class="action-btn" onclick='editSlide(<?php echo json_encode($slide); ?>)' title="Edit"><i class="fas fa-edit"></i></button>
                                <a href="?delete=<?php echo $slide['id']; ?>" class="action-btn" onclick="return confirm('Delete this slide?');" title="Delete"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<script>
    function editSlide(data) {
        document.getElementById('formTitle').innerText = 'Edit Slide';
        document.getElementById('slide_id').value = data.id;
        document.getElementById('current_file').value = data.file_path;
        document.getElementById('heading').value = data.heading;
        document.getElementById('subtext').value = data.subtext;
        document.getElementById('button_text').value = data.button_text;
        document.getElementById('sort_order').value = data.sort_order;
    }

    function resetForm() {
        document.getElementById('formTitle').innerText = 'Add New Slide';
        document.getElementById('slideForm').reset();
        document.getElementById('slide_id').value = '';
        document.getElementById('current_file').value = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const toggles = [
            { id: 'messageIcon', menu: 'messageDropdown' },
            { id: 'notificationBell', menu: 'notificationDropdown' },
            { id: 'profileIcon', menu: 'profileDropdown' }
        ];
        toggles.forEach(t => {
            const btn = document.getElementById(t.id);
            if(btn) {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
                    document.getElementById(t.menu).classList.add('show');
                });
            }
        });
        document.addEventListener('click', () => {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
        });
    });
</script>
</body>
</html>