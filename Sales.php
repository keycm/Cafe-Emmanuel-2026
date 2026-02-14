<?php
include 'session_check.php';

// --- Database Connection ---
include 'db_connect.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

// --- Fetch Recent Customers ---
$customers_result = $conn->query("SELECT * FROM cart WHERE status = 'Delivered' ORDER BY created_at DESC LIMIT 5");
$customers_data = [];
while ($row = $customers_result->fetch_assoc()) {
    $customers_data[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" type="image/png" href="logo.png">
  <title>Sales Reports - Cafe Emmanuel</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&family=Archivo+Black&family=Birthstone+Bounce:wght@500&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        margin-left: 260px; /* Aligns with fixed sidebar width */
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

    /* --- HEADER ICONS (Same as Dashboard) --- */
    .header-icons { display: flex; align-items: center; gap: 20px; }
    .icon-container {
        position: relative;
        cursor: pointer;
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        transition: 0.3s;
    }
    .icon-container:hover { background: var(--primary-red); color: white; border-color: var(--primary-red); }
    .header-badge {
        position: absolute;
        top: -5px; right: -5px;
        background-color: var(--primary-red);
        color: white;
        border-radius: 50%;
        width: 18px; height: 18px;
        font-size: 10px;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; border: 2px solid #fff;
    }
    .header-icons img { width: 40px; height: 40px; border-radius: 50%; border: 1px solid var(--border-color); }

    /* --- DASHBOARD GRIDS --- */
    .top-section-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
        margin-bottom: 30px;
    }

    .card {
        background: var(--card-bg);
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid var(--border-color);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .card-header h2 {
        font-family: var(--font-heading);
        font-size: 18px;
        font-weight: 700;
        color: var(--secondary-dark);
    }

    .date-dropdown {
        border: 1px solid var(--border-color);
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 12px;
        background: var(--bg-light);
        cursor: pointer;
    }

    /* --- TABLE STYLES --- */
    .table-wrapper { overflow-x: auto; }
    .customers-table { width: 100%; border-collapse: collapse; }
    .customers-table th {
        text-align: left;
        padding: 15px;
        font-size: 11px;
        text-transform: uppercase;
        color: var(--text-muted);
        border-bottom: 2px solid var(--bg-light);
    }
    .customers-table td {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        font-size: 14px;
    }
    .customer-name { font-weight: 600; }
    .price-text { font-weight: 700; color: var(--primary-red); }

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
        .top-section-grid { grid-template-columns: 1fr; }
        .main-content { margin-left: 0; width: 100%; }
    }
  </style>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <main class="main-content">
        <header class="main-header">
            <h1>Sales Reports</h1>
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

                <div class="icon-container" id="notificationBell"><i class="fas fa-bell"></i></div>

                <div class="icon-container" id="profileIcon" style="border:none;">
                    <img src="logo.png" alt="Admin">
                    <div class="dropdown-menu" id="profileDropdown" style="width:150px;">
                        <a href="logout.php" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="top-section-grid">
            <div class="card">
                <div class="card-header">
                    <h2>Recent Delivered Orders</h2>
                    <select class="date-dropdown"><option>Last 5 Orders</option></select>
                </div>
                <div class="table-wrapper">
                    <table class="customers-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($customers_data)): ?>
                                <?php foreach ($customers_data as $row): ?>
                                    <tr>
                                        <td class="customer-name"><?php echo htmlspecialchars($row['fullname']); ?></td>
                                        <td style="color:var(--text-muted)"><?php echo htmlspecialchars($row['address']); ?></td>
                                        <td class="price-text">₱<?php echo number_format($row['total'], 2); ?></td>
                                        <td><?php echo date("M d", strtotime($row['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" style="text-align: center; padding: 20px;">No records.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h2>Top Selling Items</h2></div>
                <div style="height: 300px;"><canvas id="sales-order-chart"></canvas></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Revenue Overview</h2>
                <select class="date-dropdown"><option>This Year</option></select>
            </div>
            <div style="height: 350px;"><canvas id="sales-chart"></canvas></div>
        </div>
    </main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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

        // --- 1. Revenue Overview Chart ---
        fetch('get_sales_data.php')
          .then(r => r.json())
          .then(data => {
            new Chart(document.getElementById("sales-chart"), { 
              type: "line",
              data: { 
                labels: data.labels, 
                datasets: [{ 
                    label: "Revenue (₱)", 
                    data: data.values, 
                    borderColor: '#E03A3E',
                    backgroundColor: 'rgba(224, 58, 62, 0.1)',
                    fill: true,
                    tension: 0.4
                }] 
              }, 
              options: { responsive: true, maintainAspectRatio: false } 
            });
          });

        // --- 2. Top Selling Items Chart ---
        fetch('get_top_selling.php')
          .then(r => r.json())
          .then(result => {
            if (result.success && result.data.length > 0) { 
              new Chart(document.getElementById("sales-order-chart"), { 
                type: "pie",
                data: { 
                  labels: result.data.map(i => i.product_name), 
                  datasets: [{ 
                    data: result.data.map(i => i.total_sold), 
                    backgroundColor: ['#E03A3E', '#222222', '#777777', '#f8f9fa', '#eeeeee']
                  }] 
                }, 
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } } 
              });
            }
          });
    });
</script>
</body>
</html>