<?php 
session_start();

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}

// We get the user's fullname from the session to pre-fill the form
$fullname = isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo.png">
    <title>Your Cart - Cafe Emmanuel</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            --bg-light: #fcfbf8;
            --border-color: #EAEAEA;
            --footer-bg-color: #1a120b;
            --footer-text-color: #ccc;
            --footer-link-hover: #FFC94A;
            
            /* Fonts */
            --font-logo-cafe: 'Archivo Black', sans-serif;
            --font-logo-emmanuel: 'Birthstone Bounce', cursive;
            --font-nav: 'Inknut Antiqua', serif;
            --font-section-heading: 'Playfair Display', serif;
            --font-body-default: 'Lato', sans-serif;
            
            --nav-height: 90px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-body-default); color: var(--text-color); background-color: var(--bg-light); line-height: 1.7; display: flex; flex-direction: column; min-height: 100vh; padding-top: var(--nav-height); }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        a { text-decoration: none; transition: 0.3s; color: inherit; }
        
        /* --- Header & Navigation --- */
        .header { position: fixed; width: 100%; top: 0; z-index: 1000; height: var(--nav-height); background: rgba(26, 18, 11, 0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .navbar { display: flex; justify-content: space-between; align-items: center; height: 100%; }
        .nav-logo { text-decoration: none; color: var(--white); display: flex; align-items: center; }
        .logo-cafe { font-family: var(--font-logo-cafe); font-size: 32px; letter-spacing: -1px; }
        .logo-emmanuel { font-family: var(--font-logo-emmanuel); font-size: 38px; margin-left: 8px; color: var(--primary-color); font-weight: 500; }
        
        .nav-menu { display: flex; list-style: none; gap: 3rem; }
        .nav-link { font-family: var(--font-nav); font-size: 16px; font-weight: 600; color: #E0E0E0; text-decoration: none; transition: color 0.3s ease; }
        .nav-link:hover, .nav-link.active { color: var(--footer-link-hover); }
        
        .nav-right-cluster { display: flex; align-items: center; gap: 1.5rem; }
        .nav-cart-link { color: var(--white); font-size: 1.2rem; text-decoration: none; transition: color 0.3s ease; position: relative; }
        .nav-cart-link:hover { color: var(--footer-link-hover); }
        
        /* Icons & Avatar */
        .nav-icon-btn { position: relative; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(255, 255, 255, 0.1); color: var(--white); font-size: 1.1rem; transition: all 0.3s ease; text-decoration: none; }
        .nav-icon-btn:hover { background: var(--footer-link-hover); color: var(--secondary-color); transform: translateY(-2px); }

        .user-avatar { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-color); transition: transform 0.3s ease; background: #fff; }
        .profile-dropdown:hover .user-avatar { transform: scale(1.05); box-shadow: 0 0 10px rgba(255, 255, 255, 0.3); }
        
        .profile-dropdown { position: relative; cursor: pointer; display: flex; align-items: center; }
        .profile-dropdown::after { content: ''; position: absolute; top: 100%; left: 0; width: 100%; height: 50px; background: transparent; }
        
        .profile-menu { display: none; position: absolute; right: 0; top: 140%; background: var(--white); min-width: 200px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); overflow: hidden; z-index: 1001; }
        .profile-dropdown:hover .profile-menu { display: block; }
        .profile-menu a { display: block; padding: 12px 20px; color: var(--text-color); font-size: 0.95rem; border-bottom: 1px solid var(--border-color); text-decoration: none; }
        .profile-menu a:hover { background: #f8f9fa; color: var(--primary-color); }
        
        .hamburger { display: none; cursor: pointer; }
        .bar { display: block; width: 25px; height: 3px; margin: 5px auto; background-color: var(--white); }

        /* --- Cart Layout --- */
        .cart-section { padding: 4rem 0 6rem; flex: 1; }
        .cart-container { display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; align-items: flex-start; }
        
        /* Left Column: Items */
        .cart-items-card { background: var(--white); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid var(--border-color); padding: 2rem; }
        .cart-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); margin-bottom: 2rem; }
        .cart-header h2 { font-family: var(--font-section-heading); font-size: 1.8rem; margin: 0; color: var(--heading-color); }
        .continue-shopping { color: var(--primary-color); font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 5px; }
        .continue-shopping:hover { text-decoration: underline; }

        .cart-item { display: flex; align-items: center; padding-bottom: 1.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); }
        .cart-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .cart-item-img { width: 90px; height: 90px; border-radius: 12px; object-fit: cover; background: #f4f4f4; margin-right: 1.5rem; }
        
        .cart-item-details { flex: 1; }
        .item-name { font-family: var(--font-section-heading); font-size: 1.2rem; font-weight: 700; margin-bottom: 0.3rem; color: var(--heading-color); }
        .item-meta { font-size: 0.85rem; color: #777; margin-bottom: 0.8rem; }
        
        .quantity-control { display: flex; align-items: center; background: #f8f8f8; border-radius: 50px; padding: 5px 10px; width: fit-content; border: 1px solid #eee; }
        .qty-btn { background: none; border: none; font-size: 1rem; padding: 0 10px; cursor: pointer; color: #555; transition: color 0.2s; }
        .qty-btn:hover { color: var(--primary-color); }
        .qty-display { font-weight: 600; min-width: 20px; text-align: center; font-size: 0.95rem; }
        
        .item-actions { text-align: right; display: flex; flex-direction: column; justify-content: space-between; height: 90px; }
        .item-price { font-weight: 700; font-size: 1.1rem; color: var(--text-color); }
        .remove-btn { background: none; border: none; color: #999; cursor: pointer; font-size: 1rem; transition: color 0.2s; align-self: flex-end; }
        .remove-btn:hover { color: #dc3545; }

        /* Right Column: Summary */
        .order-summary { background: var(--white); border-radius: 16px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid var(--border-color); position: sticky; top: calc(var(--nav-height) + 20px); }
        .order-summary h3 { font-family: var(--font-section-heading); font-size: 1.5rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color); }
        
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 0.95rem; color: #555; }
        .summary-row.total { border-top: 2px solid var(--border-color); padding-top: 1.5rem; margin-top: 1.5rem; font-weight: 700; font-size: 1.3rem; color: var(--heading-color); }
        
        .checkout-btn { width: 100%; background: var(--primary-color); color: var(--white); border: none; padding: 16px; border-radius: 12px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s; margin-top: 1.5rem; }
        .checkout-btn:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(185, 90, 75, 0.3); }
        .checkout-btn:disabled { background: #ccc; cursor: not-allowed; transform: none; box-shadow: none; }

        /* Empty State */
        #emptyCartView { text-align: center; padding: 3rem 0; display: none; }
        #emptyCartView i { font-size: 4rem; color: #eee; margin-bottom: 1.5rem; }
        #emptyCartView h3 { font-family: var(--font-section-heading); font-size: 1.8rem; margin-bottom: 1rem; }
        #emptyCartView p { color: #777; margin-bottom: 2rem; }
        .btn-shop { display: inline-block; padding: 12px 30px; background: var(--primary-color); color: var(--white); border-radius: 50px; font-weight: 600; }

        /* --- Footer --- */
        .footer { background-color: var(--footer-bg-color); color: var(--footer-text-color); padding-top: 4rem; font-size: 0.95rem; margin-top: auto; }
        .footer-content { display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 3rem; padding-bottom: 3rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .footer-brand h3 { color: var(--white); font-family: var(--font-logo-cafe); font-size: 1.8rem; margin-bottom: 1rem; }
        .footer-brand p { opacity: 0.7; margin-bottom: 1.5rem; line-height: 1.7; }
        .socials { display: flex; gap: 15px; }
        .social-link { width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; transition: 0.3s; color: var(--white); }
        .social-link:hover { background: var(--footer-link-hover); color: var(--secondary-color); }
        .footer-col h4 { color: var(--white); font-size: 1.1rem; margin-bottom: 1.5rem; font-family: var(--font-body-default); font-weight: bold; }
        .footer-links li { margin-bottom: 0.8rem; }
        .footer-links a { color: rgba(255,255,255,0.6); transition: 0.3s; }
        .footer-links a:hover { color: var(--footer-link-hover); padding-left: 5px; }
        .copyright { text-align: center; padding: 1.5rem 0; opacity: 0.5; font-size: 0.85rem; }

        /* --- Modals (Checkout) --- */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; align-items: center; justify-content: center; backdrop-filter: blur(5px); animation: fadeIn 0.3s; }
        
        /* UPDATED MODAL BOX STYLE FOR SCROLLING */
        .modal-box { 
            background: #ffffff; 
            padding: 30px; 
            border-radius: 24px; 
            width: 90%; 
            max-width: 500px; 
            position: relative; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); 
            animation: slideUp 0.4s;
            
            /* Overflow fix */
            max-height: 85vh; 
            overflow-y: auto;
        }

        .modal-close { position: absolute; top: 20px; right: 25px; font-size: 1.5rem; color: #ccc; cursor: pointer; transition: 0.2s; z-index: 10; }
        .modal-close:hover { color: var(--primary-color); }
        .modal-title { font-family: var(--font-section-heading); font-size: 1.8rem; margin-bottom: 1.5rem; color: var(--heading-color); text-align: center; }
        
        .delivery-note { background: #fff3cd; color: #856404; padding: 10px; border-radius: 8px; font-size: 0.9rem; margin-bottom: 20px; text-align: center; border: 1px solid #ffeeba; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px; font-family: var(--font-body-default); font-size: 1rem; background: #f8f9fa; transition: 0.3s; }
        .form-control:focus { border-color: var(--primary-color); background: #fff; box-shadow: 0 0 0 3px rgba(185, 90, 75, 0.1); outline: none; }
        
        .payment-options { display: flex; gap: 20px; margin-top: 10px; }
        .payment-label { display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 500; }
        
        .submit-btn { width: 100%; background: var(--secondary-color); color: white; padding: 14px; border-radius: 12px; font-size: 1rem; font-weight: 700; border: none; cursor: pointer; transition: 0.3s; margin-top: 20px; }
        .submit-btn:hover { background: #2a1e17; }
        
        .order-totals-modal { margin-top: 10px; padding-top: 10px; border-top: 1px dashed #ddd; font-size: 0.95rem; color: #555; }
        .order-totals-modal div { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .order-totals-modal .final-total { font-weight: 700; font-size: 1.2rem; color: var(--primary-color); margin-top: 10px; border-top: 2px solid #eee; padding-top: 10px; }

        /* Msg Modal */
        .msg-content { text-align: center; }
        .msg-icon { font-size: 3rem; margin-bottom: 1rem; display: block; }
        .success-icon { color: #28a745; }
        .error-icon { color: #dc3545; }
        
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); scale: 0.95; } to { opacity: 1; transform: translateY(0); scale: 1; } }

        @media (max-width: 900px) {
            .cart-container { grid-template-columns: 1fr; }
            .order-summary { position: static; margin-top: 2rem; }
        }
        @media (max-width: 768px) {
            .nav-menu, .nav-right-cluster { display: none; }
            .hamburger { display: block; }
        }
    </style>
</head>
<body>

    <header class="header">
        <nav class="navbar container">
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
                
                <div class="nav-icon-btn">
                    <?php include 'notification_bell.php'; ?>
                </div>

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

    <section class="cart-section">
        <div class="container">
            
            <div id="emptyCartView">
                <i class="fas fa-shopping-basket"></i>
                <h3>Your Cart is Empty</h3>
                <p>Looks like you haven't added anything yet. Discover our menu!</p>
                <a href="product.php" class="btn-shop">Start Shopping</a>
            </div>

            <div class="cart-container" id="cartContent">
                
                <div class="cart-items-card">
                    <div class="cart-header">
                        <h2>Your Cart</h2>
                        <a href="product.php" class="continue-shopping"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
                    </div>
                    <div id="cartItemsContainer"></div>
                </div>

                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <div id="summaryInfo"></div>
                    <div id="totalSection" class="summary-row total">
                        <span>Total</span>
                        <span>₱0.00</span>
                    </div>
                    <button class="checkout-btn" id="checkoutBtn">Proceed to Checkout</button>
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
                </div>
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="product.php">Menu</a></li>
                        <li><a href="about.php">About Us</a></li>
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

    <div class="modal-overlay" id="deliveryModal">
        <div class="modal-box">
            <span class="modal-close" onclick="closeModal('deliveryModal')">&times;</span>
            <h2 class="modal-title">Delivery Information</h2>
            <div class="delivery-note"><i class="fas fa-info-circle"></i> Fees calculated based on your location.</div>
            
            <form id="deliveryForm">
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" name="fullname" id="fullname" value="<?php echo $fullname; ?>" class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="contact">Contact Number</label>
                    <input type="tel" name="contact" id="contact" class="form-control" required placeholder="e.g. 09123456789" />
                </div>
                <div class="form-group">
                    <label for="location">Delivery Area</label>
                    <select name="location" id="location" class="form-control" required>
                        <option value="" disabled selected>Select your location</option>
                        <option value="40">San Antonio (Guagua)</option>
                        <option value="50">Guagua (Poblacion)</option>
                        <option value="70">Santa Rita</option>
                        <option value="80">Lubao</option>
                        <option value="100">Bacolor</option>
                        <option value="120">Porac</option>
                        <option value="150">San Fernando</option>
                        <option value="200">Angeles City</option>
                        <option value="250">Other (Calculated upon review)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="address">Complete Address</label>
                    <textarea name="address" id="address" rows="2" class="form-control" required placeholder="Street, Block, Lot, Landmark..."></textarea>
                </div>
                
                <div class="order-totals-modal">
                    <div><span>Subtotal:</span> <span id="modalSubtotal">₱0.00</span></div>
                    <div><span>Delivery Fee:</span> <span id="modalShipping">₱0.00</span></div>
                    <div class="final-total"><span>Total to Pay:</span> <span id="modalFinalTotal">₱0.00</span></div>
                </div>
                <br>

                <div class="form-group">
                    <label>Payment Method</label>
                    <div class="payment-options">
                        <label class="payment-label"><input type="radio" name="payment" value="COD" checked> Cash On Delivery</label>
                        <label class="payment-label"><input type="radio" name="payment" value="GCash"> GCash</label>
                    </div>
                </div>
                <button type="submit" class="submit-btn">Confirm Order</button>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="msgModal">
        <div class="modal-box" style="max-width: 400px;">
            <span class="modal-close" onclick="closeModal('msgModal')">&times;</span>
            <div class="msg-content">
                <i id="msgIcon" class="fas fa-check-circle msg-icon"></i>
                <h3 id="msgTitle" style="margin-bottom:10px;">Success</h3>
                <p id="msgText" style="color:#666;">Operation completed.</p>
                <button onclick="closeModal('msgModal')" class="btn-shop" style="margin-top:20px; cursor:pointer; border:none;">Okay</button>
            </div>
        </div>
    </div>

    <script src="JS/cart.js"></script>
    <script>
        // --- Navigation Logic ---
        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('.nav-menu');
        if(hamburger) {
            hamburger.addEventListener('click', () => {
                navMenu.classList.toggle('active'); 
            });
        }

        // --- Custom Modal Logic (Replaces Alerts) ---
        function showMessage(title, text, isError = false) {
            const modal = document.getElementById('msgModal');
            const icon = document.getElementById('msgIcon');
            
            document.getElementById('msgTitle').textContent = title;
            document.getElementById('msgText').textContent = text;
            
            if(isError) {
                icon.className = "fas fa-exclamation-circle msg-icon error-icon";
                document.getElementById('msgTitle').style.color = "#dc3545";
            } else {
                icon.className = "fas fa-check-circle msg-icon success-icon";
                document.getElementById('msgTitle').style.color = "#28a745";
            }
            
            modal.style.display = 'flex';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        // Override JS alert function globally
        window.alert = function(message) {
            showMessage('Notification', message, false);
        };
    </script>
</body>
</html>