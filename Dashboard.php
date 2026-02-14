<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'session_check.php';

// --- Database Connection ---
include 'db_connect.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Dynamic Metrics ---
$total_orders_result = $conn->query("SELECT COUNT(*) as count FROM cart");
$total_orders = $total_orders_result->fetch_assoc()['count'];

$total_delivered_result = $conn->query("SELECT COUNT(*) as count FROM cart WHERE status = 'Delivered'");
$total_delivered = $total_delivered_result->fetch_assoc()['count'];

$total_revenue_result = $conn->query("SELECT SUM(total) as sum FROM cart WHERE status = 'Delivered'");
$total_revenue = $total_revenue_result->fetch_assoc()['sum'];

if ($total_revenue === null) {
    $total_revenue = 0;
}

$total_canceled_result = $conn->query("SELECT COUNT(*) as count FROM cart WHERE status = 'Cancelled'");
$total_canceled = $total_canceled_result->fetch_assoc()['count'];

// --- DATA FOR MESSAGE ICON ---
$inquiry_count_result = $conn->query("SELECT COUNT(*) as count FROM inquiries WHERE status = 'new'");
$unread_inquiries = $inquiry_count_result ? $inquiry_count_result->fetch_assoc()['count'] : 0;

$recent_inquiries_result = $conn->query("SELECT * FROM inquiries WHERE status = 'new' ORDER BY received_at DESC LIMIT 5");
$recent_messages = [];
if ($recent_inquiries_result) {
    while ($row = $recent_inquiries_result->fetch_assoc()) {
        $recent_messages[] = $row;
    }
}

// --- Fetch All Orders ---
$all_orders_result = $conn->query("SELECT * FROM cart ORDER BY id DESC");
$all_orders = [];
while ($row = $all_orders_result->fetch_assoc()) {
    $all_orders[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard - Cafe Emmanuel</title>
  
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
        display: flex; /* Critical for side-by-side layout */
        height: 100vh;
        overflow: hidden; /* Prevent body scroll, use container scroll */
    }

    /* --- LAYOUT FIX --- */
    .dashboard-wrapper {
        display: flex;
        width: 100%;
        height: 100vh;
    }

    .main-content { 
        flex-grow: 1;
        margin-left: 260px; /* Space for the fixed sidebar */
        width: calc(100% - 260px); 
        height: 100vh;
        overflow-y: auto;
        padding: 40px;
        transition: all 0.3s ease;
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
    
    .header-icons { 
        display: flex; 
        align-items: center; 
        gap: 20px;
    }
    
    .icon-container {
        position: relative;
        cursor: pointer;
        color: var(--secondary-dark);
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        transition: all 0.3s;
    }

    .icon-container:hover {
        background: var(--primary-red);
        color: white;
        border-color: var(--primary-red);
    }

    .header-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: var(--primary-red);
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        border: 2px solid #fff;
    }

    .header-icons img { 
        width: 40px; 
        height: 40px; 
        border-radius: 50%; 
        object-fit: cover;
        border: 1px solid var(--border-color);
    }

    /* --- Metrics --- */
    .dashboard-metrics { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
        gap: 20px; 
        margin-bottom: 40px; 
    }
    
    .metric { 
        background: var(--card-bg); 
        padding: 25px; 
        border-radius: 12px; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid var(--border-color);
        border-bottom: 4px solid transparent;
        transition: transform 0.3s;
    }
    .metric:hover { 
        transform: translateY(-5px);
        border-bottom-color: var(--primary-red);
    }

    .metric-header { 
        display: flex; 
        justify-content: space-between; 
        margin-bottom: 10px; 
    }
    .metric-header p { 
        color: var(--text-muted); 
        font-weight: 600; 
        font-size: 12px; 
        text-transform: uppercase;
    }
    
    .metric-body h3 { 
        font-family: var(--font-heading);
        font-size: 28px; 
        color: var(--secondary-dark); 
    }

    /* --- Main Grid --- */
    .dashboard-main-content { 
        display: grid; 
        grid-template-columns: 1.5fr 1fr; 
        gap: 30px; 
    }
    
    .card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .card-header h2 { 
        font-family: var(--font-heading);
        font-size: 20px; 
        margin-bottom: 20px;
    }

    .search-bar input { 
        width: 100%; 
        padding: 12px 15px; 
        border-radius: 8px; 
        border: 1px solid var(--border-color); 
        background: #fcfcfc;
        margin-bottom: 20px;
    }

    .orders-list { 
        max-height: 500px; 
        overflow-y: auto; 
    }

    .order-card { 
        padding: 15px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: 0.2s;
    }
    .order-card:hover { border-color: var(--primary-red); }
    .order-card.active { background: #fff8f8; border-color: var(--primary-red); }

    .status-tag { 
        padding: 4px 10px; 
        border-radius: 4px; 
        font-size: 11px; 
        font-weight: 700; 
        text-transform: uppercase;
    }
    .status-delivered { background: #e8f5e9; color: #2e7d32; }
    .status-pending { background: #fff3e0; color: #ef6c00; }
    .status-cancelled { background: #ffebee; color: #c62828; }

    /* Dropdowns */
    .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 50px;
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

    /* Responsive */
    @media (max-width: 1200px) {
        .dashboard-main-content { grid-template-columns: 1fr; }
        .main-content { margin-left: 0; width: 100%; }
    }

    /* Billing details */
    .bill-details { background: var(--bg-light); padding: 15px; border-radius: 8px; margin-top: 20px; }
    .bill-row { display: flex; justify-content: space-between; padding: 5px 0; font-size: 14px; }
    .total-bill { border-top: 1px solid #ddd; padding-top: 10px; font-weight: 700; color: var(--primary-red); font-size: 18px; }
  </style>
</head>
<body>

    <?php include 'admin_sidebar.php'; ?>

    <main class="main-content">
      <header class="main-header">
        <h1>Dashboard Overview</h1>
        <div class="header-icons">
            
            <div class="icon-container" id="messageIcon">
                <i class="fas fa-envelope"></i>
                <?php if($unread_inquiries > 0): ?>
                    <span class="header-badge"><?php echo $unread_inquiries; ?></span>
                <?php endif; ?>
                <div class="dropdown-menu" id="messageDropdown">
                    <?php foreach ($recent_messages as $msg): ?>
                        <a href="admin_inquiries.php" class="dropdown-item">
                            <strong><?php echo htmlspecialchars($msg['first_name']); ?></strong>: 
                            <?php echo htmlspecialchars(substr($msg['message'], 0, 30)); ?>...
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="icon-container" id="notificationBell">
                <i class="fas fa-bell"></i>
                <div class="dropdown-menu" id="notificationDropdown">
                    <div class="dropdown-item">No new notifications</div>
                </div>
            </div>

            <div class="icon-container" id="profileIcon" style="border:none;">
                <img src="logo.png" alt="Admin">
                <div class="dropdown-menu" id="profileDropdown" style="width:150px;">
                    <a href="logout.php" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
      </header>

      <section class="dashboard-metrics">
        <div class="metric">
            <div class="metric-header"><p>Orders</p><i class="fas fa-box" style="color:var(--primary-red)"></i></div>
            <div class="metric-body"><h3><?php echo number_format($total_orders); ?></h3></div>
        </div>
        <div class="metric">
            <div class="metric-header"><p>Revenue</p><i class="fas fa-peso-sign" style="color:#2e7d32"></i></div>
            <div class="metric-body"><h3>₱<?php echo number_format($total_revenue, 2); ?></h3></div>
        </div>
        <div class="metric">
            <div class="metric-header"><p>Delivered</p><i class="fas fa-check-circle" style="color:#2e7d32"></i></div>
            <div class="metric-body"><h3><?php echo number_format($total_delivered); ?></h3></div>
        </div>
        <div class="metric">
            <div class="metric-header"><p>Canceled</p><i class="fas fa-times-circle" style="color:#c62828"></i></div>
            <div class="metric-body"><h3><?php echo number_format($total_canceled); ?></h3></div>
        </div>
      </section>  

      <div class="dashboard-main-content">
        <div class="card">
            <div class="card-header"><h2>Recent Orders</h2></div>
            <div class="search-bar"><input type="text" id="orderSearch" placeholder="Search orders..."></div>
            <div class="orders-list">
                <?php foreach ($all_orders as $order): 
                    $status_class = strtolower(str_replace(' ', '_', $order['status']));
                ?>
                <div class="order-card" data-order='<?php echo json_encode($order); ?>'>
                    <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                        <strong>#<?php echo str_pad($order['id'], 4, '0', STR_PAD_LEFT); ?></strong>
                        <span class="status-tag status-<?php echo $status_class; ?>"><?php echo $order['status']; ?></span>
                    </div>
                    <div style="font-size:14px; color:var(--text-muted)"><?php echo htmlspecialchars($order['fullname']); ?></div>
                    <div style="font-size:12px; margin-top:5px;"><i class="far fa-clock"></i> <?php echo date("M d, Y", strtotime($order['created_at'])); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card" id="details-view">
            <div class="card-header"><h2>Order Details</h2></div>
            <div id="details-content" style="display:none;">
                <h3 id="det-name" style="font-family:var(--font-heading)">Customer Name</h3>
                <p id="det-address" style="font-size:14px; color:var(--text-muted); margin-bottom:15px;"></p>
                
                <div class="bill-details">
                    <div id="det-items"></div>
                    <div class="bill-row total-bill">
                        <span>Total</span>
                        <span id="det-total">₱0.00</span>
                    </div>
                </div>
            </div>
            <div id="details-placeholder" style="text-align:center; padding:50px; color:#ccc;">
                <i class="fas fa-receipt fa-3x"></i>
                <p style="margin-top:10px;">Select an order to view details</p>
            </div>
        </div>
      </div>
    </main>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dropdown Logic
        const toggles = [
            { id: 'messageIcon', menu: 'messageDropdown' },
            { id: 'notificationBell', menu: 'notificationDropdown' },
            { id: 'profileIcon', menu: 'profileDropdown' }
        ];

        toggles.forEach(t => {
            document.getElementById(t.id).addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
                document.getElementById(t.menu).classList.toggle('show');
            });
        });

        document.addEventListener('click', () => {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
        });

        // Order Selection Logic
        const orderCards = document.querySelectorAll('.order-card');
        const detContent = document.getElementById('details-content');
        const detPlaceholder = document.getElementById('details-placeholder');

        orderCards.forEach(card => {
            card.addEventListener('click', () => {
                orderCards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                
                const data = JSON.parse(card.dataset.order);
                const items = JSON.parse(data.cart || '[]');

                detPlaceholder.style.display = 'none';
                detContent.style.display = 'block';

                document.getElementById('det-name').textContent = data.fullname;
                document.getElementById('det-address').textContent = data.address;
                document.getElementById('det-total').textContent = '₱' + parseFloat(data.total).toFixed(2);

                let itemsHtml = '';
                items.forEach(item => {
                    itemsHtml += `<div class="bill-row">
                        <span>${item.quantity}x ${item.name}</span>
                        <span>₱${(item.quantity * item.price).toFixed(2)}</span>
                    </div>`;
                });
                document.getElementById('det-items').innerHTML = itemsHtml;
            });
        });

        // Search
        document.getElementById('orderSearch').addEventListener('keyup', function() {
            const val = this.value.toLowerCase();
            orderCards.forEach(c => {
                c.style.display = c.innerText.toLowerCase().includes(val) ? 'block' : 'none';
            });
        });
    });
  </script>
</body>
</html>