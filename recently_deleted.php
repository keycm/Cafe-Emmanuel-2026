<?php
include 'session_check.php';
include 'db_connect.php';

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

// Fetch Deleted Orders
$orders_sql = "SELECT * FROM recently_deleted ORDER BY deleted_at DESC";
$deleted_orders = $conn->query($orders_sql);

// Fetch Deleted Products
$products_sql = "SELECT * FROM recently_deleted_products ORDER BY deleted_at DESC";
$deleted_products = $conn->query($products_sql);

// Fetch Deleted Users
$users_sql = "SELECT * FROM recently_deleted_users ORDER BY deleted_at DESC";
$deleted_users = $conn->query($users_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recycle Bin - Cafe Emmanuel</title>
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

        /* --- LAYOUT FIX --- */
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
            margin-bottom: 30px;
        }
        
        .main-header h1 { font-family: var(--font-heading); font-size: 28px; font-weight: 700; }

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

        /* --- TABS --- */
        .tabs { display: flex; gap: 10px; margin-bottom: 25px; border-bottom: 1px solid var(--border-color); }
        .tab-btn {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background: none;
            font-family: var(--font-heading);
            font-weight: 600;
            font-size: 14px;
            color: var(--text-muted);
            transition: 0.3s;
            border-bottom: 3px solid transparent;
        }
        .tab-btn.active { color: var(--primary-red); border-bottom-color: var(--primary-red); }

        /* --- CONTENT CARD --- */
        .card {
            background: var(--card-bg);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--border-color);
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* --- TABLE STYLING --- */
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
        td { padding: 15px; border-bottom: 1px solid var(--border-color); font-size: 14px; vertical-align: middle; }
        tr:hover { background-color: #fcfcfc; }

        /* --- ACTION BUTTONS --- */
        .action-icons { display: flex; gap: 8px; }
        .btn-icon { 
            width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; 
            border: 1px solid var(--border-color); background: white; cursor: pointer; transition: 0.2s; color: var(--text-muted);
        }
        .btn-icon.restore:hover { background: #e8f5e9; color: #2e7d32; border-color: #2e7d32; }
        .btn-icon.delete:hover { background: #ffebee; color: var(--primary-red); border-color: var(--primary-red); }

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

        @media (max-width: 1024px) { .main-content { margin-left: 0; width: 100%; } }
    </style>
</head>
<body>

    <?php include 'admin_sidebar.php'; ?>

    <main class="main-content">
        <header class="main-header">
            <h1>Recycle Bin</h1>
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

        <div class="tabs">
            <button class="tab-btn active" onclick="openTab(event, 'orders')">Deleted Orders</button>
            <button class="tab-btn" onclick="openTab(event, 'products')">Deleted Products</button>
            <button class="tab-btn" onclick="openTab(event, 'users')">Deleted Users</button>
        </div>

        <div class="card">
            <div id="orders" class="tab-content active">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $deleted_orders->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo str_pad($row['order_id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td><strong><?php echo htmlspecialchars($row['fullname']); ?></strong></td>
                            <td style="color:var(--primary-red); font-weight:700;">₱<?php echo number_format($row['total'], 2); ?></td>
                            <td style="font-size:12px; color:var(--text-muted);"><?php echo date("M d, Y", strtotime($row['deleted_at'])); ?></td>
                            <td>
                                <div class="action-icons">
                                    <a href="restore_delete.php?id=<?php echo $row['id']; ?>" class="btn-icon restore" title="Restore"><i class="fas fa-undo"></i></a>
                                    <a href="recently_deleted_action.php?action=permanent_delete&id=<?php echo $row['id']; ?>" class="btn-icon delete" title="Delete Permanently" onclick="return confirm('Permanent delete cannot be undone. Proceed?');"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div id="products" class="tab-content">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $deleted_products->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                            <td>₱<?php echo number_format($row['price'], 2); ?></td>
                            <td><span style="font-size:11px; background:#eee; padding:2px 8px; border-radius:4px;"><?php echo htmlspecialchars($row['category']); ?></span></td>
                            <td style="font-size:12px; color:var(--text-muted);"><?php echo date("M d, Y", strtotime($row['deleted_at'])); ?></td>
                            <td>
                                <div class="action-icons">
                                    <a href="restore_delete_product.php?id=<?php echo $row['id']; ?>" class="btn-icon restore" title="Restore"><i class="fas fa-undo"></i></a>
                                    <a href="product_actions.php?action=permanent_delete&id=<?php echo $row['id']; ?>" class="btn-icon delete" title="Delete Permanently" onclick="return confirm('Delete product permanently?');"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div id="users" class="tab-content">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $deleted_users->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['fullname']); ?></strong></td>
                            <td style="color:var(--text-muted);"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><span style="font-size:11px; text-transform:uppercase; font-weight:700;"><?php echo $row['role']; ?></span></td>
                            <td style="font-size:12px; color:var(--text-muted);"><?php echo date("M d, Y", strtotime($row['deleted_at'])); ?></td>
                            <td>
                                <div class="action-icons">
                                    <a href="user_restore_actions.php?action=restore&id=<?php echo $row['id']; ?>" class="btn-icon restore" title="Restore"><i class="fas fa-undo"></i></a>
                                    <a href="user_restore_actions.php?action=permanent_delete&id=<?php echo $row['id']; ?>" class="btn-icon delete" title="Delete Permanently" onclick="return confirm('Delete user account permanently?');"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) { tabcontent[i].style.display = "none"; }
            tablinks = document.getElementsByClassName("tab-btn");
            for (i = 0; i < tablinks.length; i++) { tablinks[i].className = tablinks[i].className.replace(" active", ""); }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
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