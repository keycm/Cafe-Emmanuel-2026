<?php
include 'session_check.php';
include 'db_connect.php';

// --- 1. HANDLE FORM SUBMISSIONS (ADD / UPDATE) ---
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = $_POST['price']; // This is the base price for Regular
    $stock = (int)$_POST['stock'];
    
    if ($action == 'add') {
        $image = "logo.png"; 
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = "uploads/" . time() . "_" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $image);
        }
        $stmt = $conn->prepare("INSERT INTO products (name, category, price, stock, image, rating) VALUES (?, ?, ?, ?, ?, 5)");
        $stmt->bind_param("ssdis", $name, $category, $price, $stock, $image);
        if ($stmt->execute()) { $message = "✅ Product added successfully!"; }
    } 
    elseif ($action == 'update') {
        $id = intval($_POST['id']);
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = "uploads/" . time() . "_" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $image);
            $stmt = $conn->prepare("UPDATE products SET name=?, category=?, price=?, stock=?, image=? WHERE id=?");
            $stmt->bind_param("ssdisi", $name, $category, $price, $stock, $image, $id);
        } else {
            $stmt = $conn->prepare("UPDATE products SET name=?, category=?, price=?, stock=? WHERE id=?");
            $stmt->bind_param("ssdii", $name, $category, $price, $stock, $id);
        }
        if ($stmt->execute()) { $message = "✅ Product updated successfully!"; }
    }
}

// --- 2. DATA FOR HEADER ICONS ---
$inquiry_count_result = $conn->query("SELECT COUNT(*) as count FROM inquiries WHERE status = 'new'");
$unread_inquiries = $inquiry_count_result ? $inquiry_count_result->fetch_assoc()['count'] : 0;

$recent_inquiries_result = $conn->query("SELECT * FROM inquiries WHERE status = 'new' ORDER BY received_at DESC LIMIT 5");
$recent_messages = [];
if ($recent_inquiries_result) {
    while ($row = $recent_inquiries_result->fetch_assoc()) { $recent_messages[] = $row; }
}

// --- 3. FETCH PRODUCTS & CATEGORIES ---
$categories = [];
$cat_result = $conn->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL ORDER BY category ASC");
while($row = $cat_result->fetch_assoc()) { $categories[] = $row['category']; }

$products_result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Product Management - Cafe Emmanuel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-red: #E03A3E;
            --secondary-dark: #222222;
            --bg-light: #f8f9fa;
            --card-bg: #FFFFFF;
            --border-color: #eeeeee;
            --text-muted: #777777;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); display: flex; height: 100vh; overflow: hidden; }

        .main-content { flex-grow: 1; margin-left: 260px; width: calc(100% - 260px); height: 100vh; overflow-y: auto; padding: 40px; }
        .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .main-header h1 { font-family: 'Montserrat', sans-serif; font-size: 28px; font-weight: 700; }

        .header-icons { display: flex; align-items: center; gap: 20px; }
        .icon-container { position: relative; cursor: pointer; width: 40px; height: 40px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color); }
        .header-badge { position: absolute; top: -5px; right: -5px; background: var(--primary-red); color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; }
        
        .dropdown-menu { display: none; position: absolute; right: 0; top: 50px; background: white; width: 250px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 8px; z-index: 1001; border: 1px solid var(--border-color); }
        .dropdown-menu.show { display: block; }
        .dropdown-item { padding: 12px; display: block; text-decoration: none; color: var(--secondary-dark); border-bottom: 1px solid var(--border-color); font-size: 13px; }

        .card { background: var(--card-bg); padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid var(--border-color); margin-bottom: 30px; }
        .card-header { margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;}
        
        /* FORM GRID UPDATED FOR SIZE/TEMP */
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 5px; }
        .form-group input, .form-group select { padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; outline: none; font-family: inherit; }
        
        .btn-main { background: var(--primary-red); color: white; border: none; padding: 12px 25px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .search-input, .filter-select { padding: 8px 15px; border-radius: 20px; border: 1px solid var(--border-color); background: #fff; font-size: 13px; outline: none; }

        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px; font-size: 11px; text-transform: uppercase; color: var(--text-muted); border-bottom: 2px solid var(--bg-light); }
        td { padding: 15px; border-bottom: 1px solid var(--border-color); font-size: 14px; }
        .product-img { width: 45px; height: 45px; object-fit: cover; border-radius: 6px; }
        
        .action-btn { background: none; border: 1px solid #ddd; padding: 6px 10px; border-radius: 6px; cursor: pointer; color: var(--text-muted); margin-right: 5px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
        .action-btn:hover { background: var(--primary-red); color: white; border-color: var(--primary-red); }

        .alert { background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c8e6c9; }
        @media (max-width: 1024px) { .main-content { margin-left: 0; width: 100%; } }
    </style>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <main class="main-content">
        <header class="main-header">
            <h1>Product Management</h1>
            <div class="header-icons">
                <div class="icon-container" id="msgBtn"><i class="fas fa-envelope"></i><?php if($unread_inquiries > 0): ?><span class="header-badge"><?php echo $unread_inquiries; ?></span><?php endif; ?>
                    <div class="dropdown-menu" id="msgDrop">
                        <?php foreach ($recent_messages as $m): ?><a href="admin_inquiries.php" class="dropdown-item"><strong><?php echo htmlspecialchars($m['first_name']); ?></strong>: <?php echo htmlspecialchars(substr($m['message'], 0, 20)); ?>...</a><?php endforeach; ?>
                    </div>
                </div>
                <div class="icon-container" style="border:none;"><img src="logo.png" style="width:100%; border-radius:50%;"></div>
            </div>
        </header>

        <?php if($message): ?><div class="alert"><?php echo $message; ?></div><?php endif; ?>

        <div class="card">
            <div class="card-header"><h2 id="form-title">Add New Product</h2></div>
            <form id="productForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" id="form-action" value="add">
                <input type="hidden" name="id" id="prod-id">
                <input type="hidden" id="base-regular-price" value="0"> <div class="form-grid">
                    <div class="form-group"><label>Product Name</label><input type="text" name="name" id="prod-name" required></div>
                    <div class="form-group"><label>Category</label>
                        <select name="category" id="prod-cat" required onchange="toggleAttributes()">
                            <option value="">Select Category</option>
                            <?php foreach($categories as $c): ?><option value="<?php echo htmlspecialchars($c); ?>"><?php echo htmlspecialchars($c); ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group attr-field" id="size-field">
                        <label>Cup Size</label>
                        <select id="prod-size" onchange="calculateAutoPrice()">
                            <option value="regular">Regular</option>
                            <option value="large">Large (+₱10)</option>
                        </select>
                    </div>

                    <div class="form-group attr-field" id="temp-field">
                        <label>Temperature</label>
                        <select id="prod-temp">
                            <option value="hot">Hot</option>
                            <option value="iced">Iced</option>
                        </select>
                    </div>

                    <div class="form-group"><label>Price (₱)</label><input type="number" step="0.01" name="price" id="prod-price" required></div>
                    <div class="form-group"><label>Stock Level</label><input type="number" name="stock" id="prod-stock" required></div>
                    <div class="form-group"><label>Image</label><input type="file" name="image" id="prod-img"></div>
                </div>
                <div style="margin-top:20px; display:flex; gap:10px;">
                    <button type="submit" class="btn-main" id="submit-btn">Add Product</button>
                    <button type="button" onclick="resetForm()" class="action-btn" style="display:none;" id="cancel-btn">Cancel Edit</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>All Products & Inventory</h2>
                <div style="display:flex; gap:10px;">
                    <input type="text" id="searchInput" class="search-input" placeholder="Search product..." onkeyup="applyFilters()">
                    <select class="filter-select" id="categoryFilter" onchange="applyFilters()">
                        <option value="all">All Categories</option>
                        <?php foreach($categories as $c): ?><option value="<?php echo strtolower($c); ?>"><?php echo htmlspecialchars($c); ?></option><?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div style="overflow-x: auto;">
                <table id="inventoryTable">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Base Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($p = $products_result->fetch_assoc()): ?>
                        <tr data-category="<?php echo strtolower($p['category']); ?>" data-name="<?php echo strtolower($p['name']); ?>">
                            <td><img src="<?php echo $p['image']; ?>" class="product-img" onerror="this.src='logo.png'"></td>
                            <td><strong><?php echo htmlspecialchars($p['name']); ?></strong></td>
                            <td><span style="color:var(--text-muted); font-size:12px;"><?php echo htmlspecialchars($p['category']); ?></span></td>
                            <td style="font-weight:700; color:var(--primary-red);">₱<?php echo number_format($p['price'], 2); ?></td>
                            <td><?php echo $p['stock']; ?> IN STOCK</td>
                            <td>
                                <button class="action-btn" onclick='editProduct(<?php echo json_encode($p); ?>)' title="Edit"><i class="fas fa-edit"></i></button>
                                <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="action-btn" onclick="return confirm('Move to Recycle Bin?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        // TOGGLE SIZE/TEMP BASED ON CATEGORY
        function toggleAttributes() {
            const cat = document.getElementById('prod-cat').value.toLowerCase();
            const attrFields = document.querySelectorAll('.attr-field');
            // Only show size/temp for coffee, tea, or beverage related categories
            const isBeverage = cat.includes('coffee') || cat.includes('tea') || cat.includes('over iced') || cat.includes('classico');
            
            attrFields.forEach(f => f.style.display = isBeverage ? 'flex' : 'none');
        }

        // AUTO-PRICE CALCULATION LOGIC
        function calculateAutoPrice() {
            const size = document.getElementById('prod-size').value;
            const basePrice = parseFloat(document.getElementById('base-regular-price').value) || 0;
            const priceInput = document.getElementById('prod-price');

            if (size === 'large' && basePrice > 0) {
                priceInput.value = (basePrice + 10).toFixed(2);
            } else if (basePrice > 0) {
                priceInput.value = basePrice.toFixed(2);
            }
        }

        function editProduct(data) {
            document.getElementById('form-title').innerText = 'Edit Product & Stock';
            document.getElementById('form-action').value = 'update';
            document.getElementById('submit-btn').innerText = 'Save Changes';
            document.getElementById('cancel-btn').style.display = 'inline-flex';
            
            document.getElementById('prod-id').value = data.id;
            document.getElementById('prod-name').value = data.name;
            document.getElementById('prod-cat').value = data.category;
            document.getElementById('prod-price').value = data.price;
            document.getElementById('base-regular-price').value = data.price; // Store for calculation
            document.getElementById('prod-stock').value = data.stock;
            
            toggleAttributes();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetForm() {
            document.getElementById('productForm').reset();
            document.getElementById('form-title').innerText = 'Add New Product';
            document.getElementById('form-action').value = 'add';
            document.getElementById('submit-btn').innerText = 'Add Product';
            document.getElementById('cancel-btn').style.display = 'none';
            document.getElementById('base-regular-price').value = 0;
            toggleAttributes();
        }

        function applyFilters() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const cat = document.getElementById('categoryFilter').value;
            const rows = document.querySelectorAll('#inventoryTable tbody tr');
            
            rows.forEach(row => {
                const nameMatch = row.getAttribute('data-name').includes(search);
                const catMatch = (cat === 'all' || row.getAttribute('data-category') === cat);
                row.style.display = (nameMatch && catMatch) ? '' : 'none';
            });
        }

        document.getElementById('msgBtn').onclick = function(e) {
            e.stopPropagation();
            document.getElementById('msgDrop').classList.toggle('show');
        }
        window.onclick = function() { document.getElementById('msgDrop').classList.remove('show'); }
    </script>
</body>
</html>