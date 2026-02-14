<?php
include 'session_check.php';
include 'db_connect.php';
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

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

// Handle status update action (from View Modal or Direct Links)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);
    
    if ($action == 'status' && isset($_GET['new_status'])) {
        $new_status = $_GET['new_status'];
        $stmt = $conn->prepare("UPDATE inquiries SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $id);
        $stmt->execute();
    } elseif ($action == 'delete') {
        $stmt = $conn->prepare("DELETE FROM inquiries WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    header("Location: admin_inquiries.php");
    exit();
}

// Query Inquiries - Sorted by Priority
$inquiries_result = $conn->query("SELECT * FROM inquiries ORDER BY 
    CASE status 
        WHEN 'new' THEN 1 
        WHEN 'in_progress' THEN 2 
        WHEN 'responded' THEN 3 
        WHEN 'closed' THEN 4 
    END, 
    received_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Inquiries - Cafe Emmanuel</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&family=Archivo+Black&family=Birthstone+Bounce:wght@500&display=swap" rel="stylesheet">

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

    /* --- LAYOUT FIX --- */
    .main-content { 
        flex-grow: 1;
        margin-left: 260px; /* Space for fixed sidebar */
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
        color: var(--secondary-dark);
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

    /* --- TABLE CARD --- */
    .table-card {
        background: var(--card-bg);
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid var(--border-color);
    }

    table { width: 100%; border-collapse: collapse; }
    th {
        text-align: left;
        padding: 15px;
        font-size: 11px;
        text-transform: uppercase;
        color: var(--text-muted);
        border-bottom: 2px solid var(--bg-light);
        letter-spacing: 0.5px;
    }
    td {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        font-size: 14px;
        vertical-align: middle;
    }
    tr:hover { background-color: #fcfcfc; }

    /* Status Badges */
    .status-badge { padding: 4px 10px; border-radius: 4px; font-weight: 700; font-size: 11px; display: inline-block; text-transform: uppercase; }
    .badge-new { background-color: #ffebee; color: #c62828; }
    .badge-in_progress { background-color: #fff3e0; color: #ef6c00; }
    .badge-responded { background-color: #e8f5e9; color: #2e7d32; }
    .badge-closed { background-color: #f5f5f5; color: #616161; }

    /* Action Buttons */
    .action-icons { display: flex; gap: 8px; }
    .btn-icon { 
        width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; 
        border: 1px solid var(--border-color); background: white; cursor: pointer; transition: 0.2s; color: var(--text-muted); text-decoration: none;
    }
    .btn-icon:hover { background-color: var(--primary-red); color: white; border-color: var(--primary-red); }

    /* Modal */
    .modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; backdrop-filter: blur(3px); }
    .modal.active { display: flex; }
    .modal-container { background: white; border-radius: 12px; width: 90%; max-width: 600px; box-shadow: 0 15px 50px rgba(0,0,0,0.1); }
    .modal-header { padding: 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; }
    .modal-body { padding: 25px; }

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
    .dropdown-item {
        padding: 12px 15px;
        display: block;
        text-decoration: none;
        color: var(--secondary-dark);
        font-size: 13px;
        border-bottom: 1px solid var(--border-color);
    }
    .dropdown-item:hover { background: var(--bg-light); color: var(--primary-red); }

    @media (max-width: 1024px) {
        .main-content { margin-left: 0; width: 100%; }
    }
</style>
</head>
<body>
<div class="dashboard-wrapper" style="display:flex; width:100%;">
  <?php include 'admin_sidebar.php'; ?>
  <main class="main-content">
    <header class="main-header">
      <h1>Customer Inquiries</h1>
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

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Sender</th>
                    <th>Message Preview</th>
                    <th>Received</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($inquiries_result->num_rows > 0): ?>
                <?php while ($row = $inquiries_result->fetch_assoc()): 
                    $jsonData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    $status = $row['status'] ?? 'new';
                ?>
                <tr>
                    <td>
                        <div style="font-weight:600;"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></div>
                        <div style="font-size:11px; color:var(--text-muted);"><?php echo htmlspecialchars($row['email']); ?></div>
                    </td>
                    <td style="max-width:300px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        <?php echo htmlspecialchars($row['message']); ?>
                    </td>
                    <td style="font-size:12px; color:var(--text-muted);">
                        <?php echo date("M d, Y", strtotime($row['received_at'])); ?>
                    </td>
                    <td>
                        <span class="status-badge badge-<?php echo $status; ?>"><?php echo ucwords(str_replace('_', ' ', $status)); ?></span>
                    </td>
                    <td>
                        <div class="action-icons">
                            <button onclick="openViewModal(<?php echo $jsonData; ?>)" class="btn-icon" title="View"><i class="fas fa-eye"></i></button>
                            <button onclick="openReplyModal(<?php echo $jsonData; ?>)" class="btn-icon" title="Reply"><i class="fas fa-reply"></i></button>
                            <a href="?action=delete&id=<?php echo $row['id']; ?>" class="btn-icon" title="Delete" onclick="return confirm('Delete permanently?')"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center; padding:40px; color:var(--text-muted);">No inquiries found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
  </main>
</div>

<div id="viewModal" class="modal">
    <div class="modal-container">
        <div class="modal-header">
            <h2 style="font-family:var(--font-heading); font-size:18px;">Inquiry Details</h2>
            <button onclick="closeModal('viewModal')" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
        </div>
        <div class="modal-body">
            <div id="viewMeta" style="margin-bottom:20px; font-size:13px; color:var(--text-muted);"></div>
            <div style="background:var(--bg-light); padding:15px; border-radius:8px; margin-bottom:20px;" id="viewMessage"></div>
            
            <form action="admin_inquiries.php" method="GET" style="display:flex; gap:10px;">
                <input type="hidden" name="action" value="status">
                <input type="hidden" name="id" id="viewId">
                <select name="new_status" id="viewStatusSelect" style="flex-grow:1; padding:8px; border:1px solid var(--border-color); border-radius:6px;">
                    <option value="new">New</option>
                    <option value="in_progress">In Progress</option>
                    <option value="responded">Responded</option>
                    <option value="closed">Closed</option>
                </select>
                <button type="submit" style="background:var(--secondary-dark); color:white; border:none; padding:8px 15px; border-radius:6px; cursor:pointer;">Update Status</button>
            </form>
        </div>
    </div>
</div>

<div id="replyModal" class="modal">
    <div class="modal-container">
        <div class="modal-header">
            <h2 style="font-family:var(--font-heading); font-size:18px;">Reply to Inquiry</h2>
            <button onclick="closeModal('replyModal')" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="send_inquiry_response.php">
                <input type="hidden" name="inquiry_id" id="replyId">
                <div style="margin-bottom:15px;">
                    <label style="font-size:13px; font-weight:600;">Response Message:</label>
                    <textarea name="response_message" id="responseMessage" required style="width:100%; height:120px; padding:10px; border:1px solid var(--border-color); border-radius:6px; margin-top:5px;"></textarea>
                </div>
                <div style="display:flex; gap:10px;">
                    <button type="submit" style="flex-grow:1; background:var(--primary-red); color:white; border:none; padding:12px; border-radius:6px; cursor:pointer; font-weight:600;">Send Reply</button>
                    <button type="button" onclick="closeModal('replyModal')" style="padding:12px; background:var(--bg-light); border:1px solid var(--border-color); border-radius:6px; cursor:pointer;">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function openViewModal(data) {
    document.getElementById('viewId').value = data.id;
    document.getElementById('viewMeta').innerHTML = `<strong>From:</strong> ${data.first_name} ${data.last_name} <br> <strong>Email:</strong> ${data.email}`;
    document.getElementById('viewMessage').textContent = data.message;
    document.getElementById('viewStatusSelect').value = data.status;
    document.getElementById('viewModal').classList.add('active');
}

function openReplyModal(data) {
    document.getElementById('replyId').value = data.id;
    document.getElementById('replyModal').classList.add('active');
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
<?php $conn->close(); ?>