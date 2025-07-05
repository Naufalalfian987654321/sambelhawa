<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sambal Hawa - Sambal Pedas Terbaik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sambal Hawa - Sambal pedas terbaik dengan berbagai varian rasa yang menggugah selera">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700;900&family=Inter:wght@400;600&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .header-container {
            background: linear-gradient(135deg, #fffbe7 0%, #ffe5e0 100%);
            box-shadow: 0 4px 20px rgba(255, 152, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #ff9800, #ffb74d);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .logo-text {
            display: flex;
            flex-direction: column;
        }
        
        .logo-title {
            font-family: 'Merriweather', serif;
            font-size: 1.8rem;
            font-weight: 900;
            color: #b71c1c;
            line-height: 1;
            text-shadow: 0 2px 4px rgba(183, 28, 28, 0.1);
        }
        
        .logo-subtitle {
            font-size: 0.9rem;
            color: #ff9800;
            font-weight: 600;
            letter-spacing: 1px;
        }
        
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .nav-link {
            color: #b71c1c;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 152, 0, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .nav-link:hover::before {
            left: 100%;
        }
        
        .nav-link:hover {
            background: linear-gradient(135deg, #ff9800, #ffb74d);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, #b71c1c, #d32f2f);
            color: white;
            box-shadow: 0 4px 15px rgba(183, 28, 28, 0.3);
        }
        
        .cart-icon {
            position: relative;
            background: linear-gradient(135deg, #ff9800, #ffb74d);
            color: white;
            padding: 0.75rem;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
        }
        
        .cart-icon:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 20px rgba(255, 152, 0, 0.4);
        }
        
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #b71c1c;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #b71c1c;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .header-content {
                padding: 0 1rem;
                height: 70px;
            }
            
            .logo-title {
                font-size: 1.5rem;
            }
            
            .nav-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                padding: 1rem;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            }
            
            .nav-menu.active {
                display: flex;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .nav-link {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header class="header-container">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-pepper-hot text-2xl text-white"></i>
                </div>
                <div class="logo-text">
                    <div class="logo-title">Sambal Hawa</div>
                    <div class="logo-subtitle">PEDAS TERBAIK</div>
                </div>
            </div>
            
            <nav class="nav-menu" id="navMenu">
                <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home" style="margin-right: 0.5rem;"></i>
                    Beranda
                </a>
                <a href="products.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">
                    <i class="fas fa-box" style="margin-right: 0.5rem;"></i>
                    Katalog
                </a>
                <a href="cart.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart" style="margin-right: 0.5rem;"></i>
                    Keranjang
                </a>
            </nav>
            
            <a href="cart.php" class="cart-icon">
                <i class="fas fa-shopping-bag"></i>
                <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <span class="cart-badge"><?php echo count($_SESSION['cart']); ?></span>
                <?php endif; ?>
            </a>
            
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>
    
    <script>
        function toggleMobileMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const navMenu = document.getElementById('navMenu');
            const mobileBtn = document.querySelector('.mobile-menu-btn');
            
            if (!navMenu.contains(event.target) && !mobileBtn.contains(event.target)) {
                navMenu.classList.remove('active');
            }
        });
    </script> 