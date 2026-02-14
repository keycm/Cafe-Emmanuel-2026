<?php
// This line gets the current page's filename, e.g., "Dashboard.php"
$current_page = basename($_SERVER['SCRIPT_NAME']);
require_once __DIR__ . '/config.php';
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@400;500;600&family=Archivo+Black&family=Birthstone+Bounce:wght@500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* --- NEW UI STYLE MATCHING CAFE EMMANUEL --- */
:root {
    --primary-red: #E03A3E;
    --sidebar-bg: #ffffff;
    --text-dark: #222222;
    --text-muted: #777777;
    --nav-hover-bg: #f8f9fa;
    --transition: all 0.3s ease;
}

.sidebar {
    width: 260px;
    height: 100vh;
    background: var(--sidebar-bg);
    position: fixed;
    left: 0;
    top: 0;
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 15px rgba(0, 0, 0, 0.05);
    z-index: 1000;
    font-family: 'Poppins', sans-serif;
}

.sidebar-logo-link {
    text-decoration: none;
    padding: 30px 15px;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 10px;
    display: flex;
    justify-content: center; /* Center horizontally in the sidebar */
    width: 100%;
}

.sidebar-logo-text {
    display: flex;
    flex-direction: column;
    align-items: center; /* Center the two spans */
    text-align: center;
    line-height: 1;
}

.logo-main {
    font-family: 'Archivo Black', sans-serif;
    font-size: 24px;
    color: var(--text-dark);
    text-transform: uppercase;
}

.logo-sub {
    font-family: 'Birthstone Bounce', cursive;
    font-size: 28px;
    color: var(--primary-red);
    margin-top: -5px;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    padding: 10px 15px;
    flex-grow: 1;
    overflow-y: auto;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    text-decoration: none;
    color: var(--text-dark);
    font-size: 14px;
    font-weight: 500;
    border-radius: 8px;
    margin-bottom: 5px;
    transition: var(--transition);
    font-family: 'Montserrat', sans-serif;
}

.nav-item i {
    width: 20px;
    margin-right: 12px;
    font-size: 18px;
    color: var(--text-muted);
    transition: var(--transition);
}

.nav-item:hover {
    background-color: var(--nav-hover-bg);
    color: var(--primary-red);
}

.nav-item:hover i {
    color: var(--primary-red);
}

.nav-item.active {
    background-color: var(--primary-red);
    color: #ffffff;
}

.nav-item.active i {
    color: #ffffff;
}

.nav-item-logout {
    margin-top: auto;
    border-top: 1px solid #f0f0f0;
    padding-top: 20px;
    margin-bottom: 20px;
}

.nav-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #bbb;
    margin: 15px 0 10px 15px;
    font-weight: 700;
}
</style>

<aside class="sidebar">
   <a href="Dashboard.php" class="sidebar-logo-link">
       <div class="sidebar-logo-text">
            <span class="logo-main">Cafe</span>
            <span class="logo-sub">Emmanuel</span>
       </div>
   </a>

    <nav class="sidebar-nav">
        <div class="nav-label">Main Menu</div>
        <a href="Dashboard.php" class="nav-item <?php if ($current_page == 'Dashboard.php') echo 'active'; ?>">
            <i class="fas fa-home fa-fw"></i><span>Dashboard</span>
        </a>
        <a href="Sales.php" class="nav-item <?php if ($current_page == 'Sales.php') echo 'active'; ?>">
            <i class="fas fa-chart-bar fa-fw"></i><span>Reports</span>
        </a>
        <a href="Orders.php" class="nav-item <?php if ($current_page == 'Orders.php') echo 'active'; ?>">
            <i class="fas fa-box-open fa-fw"></i><span>Orders</span>
        </a>
        <a href="admin_inquiries.php" class="nav-item <?php if ($current_page == 'admin_inquiries.php') echo 'active'; ?>">
            <i class="fas fa-envelope fa-fw"></i><span>Inquiries</span>
        </a>

        <div class="nav-label">Management</div>
        
        <a href="practiceaddproduct.php" class="nav-item <?php if ($current_page == 'practiceaddproduct.php') echo 'active'; ?>">
            <i class="fas fa-tasks fa-fw"></i><span>Manage Products</span>
        </a>
        <a href="manage_hero.php" class="nav-item <?php if ($current_page == 'manage_hero.php') echo 'active'; ?>">
            <i class="fas fa-image fa-fw"></i><span>Hero Section</span>
        </a>
        
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin'): ?>
        <div class="nav-label">System Admin</div>
        <a href="user_accounts.php" class="nav-item <?php if ($current_page == 'user_accounts.php') echo 'active'; ?>">
            <i class="fas fa-users fa-fw"></i><span>User Accounts</span>
        </a>
        <a href="recently_deleted.php" class="nav-item <?php if ($current_page == 'recently_deleted.php') echo 'active'; ?>">
            <i class="fas fa-trash-alt fa-fw"></i><span>Recycle Bin</span>
        </a>
        <a href="audit_logs_page.php" class="nav-item <?php if ($current_page == 'audit_logs_page.php') echo 'active'; ?>">
            <i class="fas fa-clipboard-list fa-fw"></i><span>Audit Logs</span>
        </a>
        <?php endif; ?>
        
        <div class="nav-item-logout">
            <a href="logout.php" class="nav-item">
                <i class="fas fa-sign-out-alt fa-fw"></i><span>Log Out</span>
            </a>
        </div>
    </nav>
</aside>