<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Home') - T-Store</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            color: #2c3e50;
        }

        /* Header/Navbar */
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #667eea;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-menu {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-link {
            text-decoration: none;
            color: #2c3e50;
            font-weight: 500;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link:hover {
            color: #667eea;
        }

        .nav-link.active {
            color: #667eea;
            font-weight: 600;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .cart-icon {
            position: relative;
            font-size: 24px;
            cursor: pointer;
            color: #2c3e50;
            transition: color 0.3s;
        }

        .cart-icon:hover {
            color: #667eea;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e74c3c;
            color: white;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .btn-login, .btn-register {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            border: none;
        }

        .btn-login {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-login:hover {
            background: #667eea;
            color: white;
        }

        .btn-register {
            background: #667eea;
            color: white;
        }

        .btn-register:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 40px 30px;
            margin-top: 60px;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }

        .footer-section h3 {
            margin-bottom: 15px;
            font-size: 18px;
        }

        .footer-section p, .footer-section a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            line-height: 2;
        }

        .footer-section a:hover {
            color: white;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.6);
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #2c3e50;
        }

        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            background: white;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 320px;
            animation: slideInRight 0.3s ease;
            border-left: 4px solid;
        }

        .toast.success {
            border-left-color: #27ae60;
        }

        .toast.error {
            border-left-color: #e74c3c;
        }

        .toast-icon {
            font-size: 24px;
        }

        .toast-message {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
            color: #2c3e50;
        }

        .toast-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #7f8c8d;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toast-close:hover {
            color: #2c3e50;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }

        /* Cart Icon Pulse Animation */
        .cart-icon.pulse {
            animation: pulse 0.6s ease;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            25% {
                transform: scale(1.15);
            }
            50% {
                transform: scale(1.05);
            }
            75% {
                transform: scale(1.12);
            }
        }

        .cart-badge.bump {
            animation: bump 0.4s ease;
        }

        @keyframes bump {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.3);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }

            .nav-menu {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 80%;
                max-width: 300px;
                height: calc(100vh - 70px);
                background: white;
                flex-direction: column;
                align-items: flex-start;
                padding: 20px;
                box-shadow: 2px 0 10px rgba(0,0,0,0.1);
                transition: left 0.3s ease;
            }

            .nav-menu.active {
                left: 0;
            }

            .navbar-container {
                padding: 15px 20px;
            }

            .main-content {
                padding: 20px 15px;
            }

            .user-menu {
                gap: 10px;
            }

            .btn-login, .btn-register {
                padding: 8px 15px;
                font-size: 14px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('home') }}" class="logo">
                <span>🛍️</span>
                <span>T-Store</span>
            </a>

            <button class="mobile-toggle" id="mobileToggle">☰</button>

            <div class="nav-menu" id="navMenu">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <span>🏠</span>
                    <span>Home</span>
                </a>
                
                @auth
                    {{-- Authenticated User - Link to Wishlist --}}
                    <a href="{{ route('wishlist.index') }}" class="nav-link {{ request()->routeIs('wishlist.*') ? 'active' : '' }}" style="position: relative;">
                        <span>❤️</span>
                        <span>Wishlist</span>
                        <span class="cart-badge" id="wishlistBadge" style="display: none;">0</span>
                    </a>
                @else
                    {{-- Guest User - Redirect to Login --}}
                    <a href="javascript:void(0)" onclick="redirectToLogin('wishlist')" class="nav-link" style="position: relative;" title="Login untuk melihat wishlist">
                        <span>❤️</span>
                        <span>Wishlist</span>
                    </a>
                @endauth
            </div>

            <div class="user-menu">
                @auth
                    {{-- Authenticated User --}}
                    <a href="{{ route('cart.index') }}" class="cart-icon" style="text-decoration: none; color: inherit;">
                        🛒
                        <span class="cart-badge" id="cartBadge">0</span>
                    </a>

                    <div class="user-info">
                        <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        <span style="font-weight: 600; display: none;" class="user-name">{{ auth()->user()->name }}</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                @else
                    {{-- Guest User --}}
                    <a href="javascript:void(0)" onclick="redirectToLogin('cart')" class="cart-icon" style="text-decoration: none; color: inherit;" title="Login untuk melihat keranjang">
                        🛒
                    </a>
                    
                    <a href="{{ route('login') }}" class="btn-login">Login</a>
                    <a href="{{ route('register') }}" class="btn-register">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                <strong>✓</strong> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <strong>✗</strong> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>🛍️ T-Store</h3>
                <p>Toko elektronik terpercaya dengan produk berkualitas dan harga terjangkau.</p>
            </div>
            <div class="footer-section">
                <h3>Layanan</h3>
                <a href="#">Tentang Kami</a><br>
                <a href="#">Hubungi Kami</a><br>
                <a href="#">FAQ</a><br>
            </div>
            <div class="footer-section">
                <h3>Kebijakan</h3>
                <a href="#">Syarat & Ketentuan</a><br>
                <a href="#">Kebijakan Privasi</a><br>
                <a href="#">Kebijakan Pengembalian</a><br>
            </div>
            <div class="footer-section">
                <h3>Hubungi Kami</h3>
                <p>📧 support@tstore.com</p>
                <p>📞 +62 812-3456-7890</p>
                <p>📍 Jakarta, Indonesia</p>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} T-Store. All Rights Reserved.
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const navMenu = document.getElementById('navMenu');

        if (mobileToggle && navMenu) {
            mobileToggle.addEventListener('click', function() {
                navMenu.classList.toggle('active');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!navMenu.contains(e.target) && !mobileToggle.contains(e.target)) {
                    navMenu.classList.remove('active');
                }
            });
        }

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);

        @auth
        // Load Cart Count on Page Load (Only for authenticated users)
        function loadCartCount() {
            fetch('{{ route('cart.count') }}', {
                headers: {
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                updateCartBadge(data.count, false);
            })
            .catch(error => console.error('Error loading cart count:', error));
        }

        // Load Wishlist Count
        function loadWishlistCount() {
            fetch('{{ route('wishlist.count') }}', {
                headers: {
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                updateWishlistBadge(data.count, false);
            })
            .catch(error => console.error('Error loading wishlist count:', error));
        }

        // Update Cart Badge
        function updateCartBadge(count, animate = true) {
            const badge = document.getElementById('cartBadge');
            const cartIcon = document.querySelector('.cart-icon');
            
            if (badge) {
                badge.textContent = count;
                
                if (count === 0) {
                    badge.style.display = 'none';
                } else {
                    badge.style.display = 'flex';
                    
                    if (animate) {
                        badge.classList.add('bump');
                        setTimeout(() => badge.classList.remove('bump'), 400);
                        
                        if (cartIcon) {
                            cartIcon.classList.add('pulse');
                            setTimeout(() => cartIcon.classList.remove('pulse'), 600);
                        }
                    }
                }
            }
        }

        // Update Wishlist Badge
        function updateWishlistBadge(count, animate = true) {
            const badge = document.getElementById('wishlistBadge');
            
            if (badge) {
                badge.textContent = count;
                
                if (count === 0) {
                    badge.style.display = 'none';
                } else {
                    badge.style.display = 'flex';
                    
                    if (animate) {
                        badge.classList.add('bump');
                        setTimeout(() => badge.classList.remove('bump'), 400);
                    }
                }
            }
        }

        // Load on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCartCount();
            loadWishlistCount();
        });
        @endauth

        // Toast Notification System
        function showToast(message, type = 'success', duration = 3000) {
            const container = document.getElementById('toastContainer');
            
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const icon = type === 'success' ? '✓' : '✗';
            
            toast.innerHTML = `
                <span class="toast-icon">${icon}</span>
                <span class="toast-message">${message}</span>
                <button class="toast-close" onclick="this.parentElement.remove()">×</button>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }

        // Global function to handle guest add to cart (redirect to login)
        window.handleGuestAddToCart = function() {
            showToast('Silakan login terlebih dahulu untuk menambahkan ke keranjang', 'error');
            setTimeout(() => {
                window.location.href = '{{ route('login') }}';
            }, 1500);
        };

        @guest
        // Guest User - Redirect to Login Function
        function redirectToLogin(action) {
            let message = '';
            let redirectUrl = '{{ route('login') }}';
            
            if (action === 'cart') {
                message = 'Silakan login terlebih dahulu untuk melihat keranjang';
            } else if (action === 'wishlist') {
                message = 'Silakan login terlebih dahulu untuk melihat wishlist';
            } else {
                message = 'Silakan login terlebih dahulu';
            }
            
            showToast(message, 'error', 2000);
            
            setTimeout(() => {
                window.location.href = redirectUrl;
            }, 1500);
        }
        @endguest
    </script>

    @stack('scripts')
</body>
</html>