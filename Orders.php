<?php
include 'session_check.php';
include 'db_connect.php';

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
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

$sql = "SELECT * FROM cart ORDER BY id DESC";
$result = $conn->query($sql);

function getInitials($name) {
    $words = explode(" ", $name);
    $initials = "";
    foreach ($words as $w) { if (!empty($w)) { $initials .= strtoupper($w[0]); } }
    return substr($initials, 0, 2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel="icon" type="image/png" href="logo.png">
<title>Orders - Cafe Emmanuel</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&family=Archivo+Black&family=Birthstone+Bounce:wght@500&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

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

    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table th {
        text-align: left;
        padding: 15px;
        font-size: 11px;
        text-transform: uppercase;
        color: var(--text-muted);
        border-bottom: 2px solid var(--bg-light);
        letter-spacing: 0.5px;
    }

    .orders-table td {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        font-size: 14px;
        vertical-align: middle;
    }

    .orders-table tr:hover { background-color: #fcfcfc; }

    .customer-cell { display: flex; align-items: center; gap: 12px; }
    .avatar { 
        width: 35px; height: 35px; border-radius: 50%; 
        background-color: #fceced; color: var(--primary-red);
        display: flex; align-items: center; justify-content: center; 
        font-weight: 700; font-size: 12px;
    }

    /* --- STATUS PILLS --- */
    .status-pill { 
        padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 700; 
        text-transform: uppercase; display: inline-block;
    }
    .status-pending { background-color: #fff3e0; color: #ef6c00; }
    .status-confirmed, .status-processing { background-color: #e3f2fd; color: #1565c0; }
    .status-delivered, .status-completed { background-color: #e8f5e9; color: #2e7d32; }
    .status-cancelled { background-color: #ffebee; color: #c62828; }

    /* --- ACTION BUTTONS --- */
    .action-icons { display: flex; gap: 5px; }
    .action-btn { 
        width: 32px; height: 32px; border-radius: 6px; border: 1px solid var(--border-color);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: 0.2s; background: white; color: var(--text-muted);
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
        <h1>Orders Management</h1>
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
        <div style="overflow-x: auto;">
            <table class="orders-table">
              <thead>
                <tr>
                  <th>Order ID</th> 
                  <th>Customer</th> 
                  <th>Items</th> 
                  <th>Total</th> 
                  <th>Status</th> 
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                  <?php
                    $cart_items = json_decode($row['cart'], true);
                    $status_raw = $row['status'] ?? 'Pending';
                    $status_class = strtolower(str_replace(' ', '_', $status_raw));
                    $row_data = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                  ?>
                  <tr>
                    <td>
                        <span style="font-weight:700;">#<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></span><br>
                        <small style="color:var(--text-muted); font-size:10px;"><?php echo date("M d, H:i", strtotime($row['created_at'])); ?></small>
                    </td>
                    <td>
                        <div class="customer-cell">
                            <div class="avatar"><?php echo getInitials($row['fullname']); ?></div>
                            <div>
                                <div style="font-weight:600;"><?php echo htmlspecialchars($row['fullname']); ?></div>
                                <div style="font-size:11px; color:var(--text-muted);"><?php echo htmlspecialchars($row['contact']); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:12px; color:var(--secondary-dark);">
                        <?php
                            if ($cart_items && is_array($cart_items)) {
                                $count = 0;
                                foreach($cart_items as $item) $count += ($item['quantity'] ?? 1);
                                echo $count . " Items";
                            }
                        ?>
                        </div>
                    </td>
                    <td><strong style="color:var(--primary-red);">₱<?php echo number_format($row['total'], 2); ?></strong></td>
                    <td><div class="status-pill status-<?php echo $status_class; ?>"><?php echo htmlspecialchars($status_raw); ?></div></td>
                    <td>
                      <div class="action-icons">
                        <button class="action-btn" onclick="printReceipt(this)" data-order='<?php echo $row_data; ?>' title="Print"><i class="fas fa-print"></i></button>
                        
                        <?php if ($status_class === 'pending') : ?>
                            <form method="POST" action="update_order.php">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>"><input type="hidden" name="action" value="accept">
                                <button type="submit" class="action-btn" title="Confirm"><i class="fas fa-check"></i></button>
                            </form>
                        <?php elseif ($status_class === 'confirmed') : ?>
                            <form method="POST" action="update_order.php">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>"><input type="hidden" name="action" value="processing">
                                <button type="submit" class="action-btn" title="Prepare"><i class="fas fa-utensils"></i></button>
                            </form>
                        <?php elseif ($status_class === 'processing') : ?>
                            <form method="POST" action="update_order.php">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>"><input type="hidden" name="action" value="out_for_delivery">
                                <button type="submit" class="action-btn" title="Ship"><i class="fas fa-motorcycle"></i></button>
                            </form>
                        <?php elseif ($status_class === 'out_for_delivery') : ?>
                            <form method="POST" action="update_order.php">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>"><input type="hidden" name="action" value="completed">
                                <button type="submit" class="action-btn" title="Deliver"><i class="fas fa-check-double"></i></button>
                            </form>
                        <?php endif; ?>

                        <form method="POST" action="update_order.php" onsubmit="return confirm('Move to trash?');">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>"><input type="hidden" name="action" value="delete">
                            <button type="submit" class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
        </div>
    </div>
  </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Dropdown Logic
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
                const menu = document.getElementById(t.menu);
                if(menu) menu.classList.add('show');
            });
        }
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
    });
});

function printReceipt(btn) {
    const orderData = JSON.parse(btn.getAttribute('data-order'));
    const items = JSON.parse(orderData.cart || '[]');
    let html = `<html><head><title>Receipt #${orderData.id}</title><style>
        body{font-family:'Courier New',monospace; padding:20px; text-align:center;}
        .brand{font-size:20px; font-weight:bold;}
        .divider{border-top:1px dashed #000; margin:10px 0;}
        .item-row{display:flex; justify-content:space-between; font-size:12px; margin-bottom:5px;}
    </style></head><body>
        <div class="brand">CAFE EMMANUEL</div>
        <div class="divider"></div>
        <div>Order #${String(orderData.id).padStart(4,'0')}</div>
        <div>Customer: ${orderData.fullname}</div>
        <div class="divider"></div>`;
    
    items.forEach(it => {
        html += `<div class="item-row"><span>${it.quantity}x ${it.name}</span><span>₱${(it.quantity * it.price).toFixed(2)}</span></div>`;
    });
    
    html += `<div class="divider"></div><div style="font-weight:bold;">Total: ₱${Number(orderData.total).toFixed(2)}</div></body></html>`;
    const win = window.open('','','width=400,height=600');
    win.document.write(html); win.document.close(); win.print();
}
</script>
</body>
</html>
<?php $conn->close(); ?>