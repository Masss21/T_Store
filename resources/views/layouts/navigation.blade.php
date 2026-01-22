<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('home') }}">
                🛍️ T-Store
            </a>
        </div>

        <div class="navbar-menu">
            @auth
                @if(auth()->user()->isAdmin())
                    {{-- Admin Navigation --}}
                    <a href="{{ route('admin.dashboard') }}" class="navbar-link">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="navbar-link">
                        📦 Produk
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="navbar-link">
                        👥 Pelanggan
                    </a>
                @else
                    {{-- User Navigation --}}
                    <a href="{{ route('home') }}" class="navbar-link">
                        🏠 Home
                    </a>
                    <a href="{{ route('cart.index') }}" class="navbar-link">
                        🛒 Keranjang
                        <span class="cart-badge" id="cartCount">0</span>
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="navbar-link">
                        ❤️ Wishlist
                    </a>
                @endif

                {{-- User Info & Logout --}}
                <div class="navbar-user">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
                    </form>
                </div>
            @else
                {{-- Guest Navigation --}}
                <a href="{{ route('login') }}" class="navbar-link">Login</a>
                <a href="{{ route('register') }}" class="navbar-link">Register</a>
            @endauth
        </div>
    </div>
</nav>

<style>
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
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .navbar-brand a {
        font-size: 1.5rem;
        font-weight: bold;
        color: #2563eb;
        text-decoration: none;
    }

    .navbar-menu {
        display: flex;
        gap: 2rem;
        align-items: center;
    }

    .navbar-link {
        color: #1f2937;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
        position: relative;
    }

    .navbar-link:hover {
        color: #2563eb;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -10px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: bold;
    }

    .navbar-user {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-name {
        font-weight: 600;
        color: #1f2937;
    }

    .btn-logout {
        background: #ef4444;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.3s;
    }

    .btn-logout:hover {
        background: #dc2626;
    }
</style>