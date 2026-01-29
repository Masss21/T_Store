@extends('user.layouts.app')

@section('title', 'Home')

@section('content')
<style>

    @push('styles')
<style>
    /* Loading Overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.3);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-overlay.active {
        display: flex;
    }

    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .search-results-info {
        padding: 15px 20px;
        background: #e7f3ff;
        border-left: 4px solid #2196F3;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #1976D2;
    }
</style>
@endpush

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 40px;
        border-radius: 20px;
        margin-bottom: 40px;
        text-align: center;
    }

    .hero-title {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 15px;
        animation: fadeInUp 0.6s ease;
    }

    .hero-subtitle {
        font-size: 20px;
        opacity: 0.9;
        animation: fadeInUp 0.8s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Search & Filter Section */
    .search-filter-section {
        background: white;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .search-bar {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .search-input {
        flex: 1;
        padding: 12px 20px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s;
    }

    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .search-btn {
        padding: 12px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .search-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    .filters-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-label {
        font-size: 13px;
        font-weight: 600;
        color: #2c3e50;
    }

    .filter-select,
    .filter-input {
        padding: 10px 12px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .filter-select:focus,
    .filter-input:focus {
        outline: none;
        border-color: #667eea;
    }

    /* Featured Products */
    .section-title {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
        cursor: pointer;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    .product-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        background: #f8f9fa;
    }

    .product-info {
        padding: 20px;
    }

    .product-category {
        font-size: 12px;
        color: #7f8c8d;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .product-name {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-price {
        font-size: 24px;
        font-weight: 700;
        color: #e74c3c;
        margin-bottom: 10px;
    }

    .product-stock {
        font-size: 13px;
        color: #27ae60;
        margin-bottom: 15px;
    }

    .product-stock.low {
        color: #f39c12;
    }

    .product-stock.out {
        color: #e74c3c;
    }

    .product-actions {
        display: flex;
        gap: 10px;
    }

    .btn-add-cart {
        flex: 1;
        padding: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-add-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-wishlist {
        padding: 10px 15px;
        background: #ecf0f1;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-wishlist:hover {
        background: #e74c3c;
        color: white;
        transform: scale(1.1);
    }

    .featured-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #f39c12;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .product-card {
        position: relative;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 40px;
    }

    .pagination a,
    .pagination span {
        padding: 10px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        color: #2c3e50;
        text-decoration: none;
        transition: all 0.3s;
    }

    .pagination a:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .pagination .active span {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #7f8c8d;
    }

    .empty-state .icon {
        font-size: 80px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 32px;
        }

        .hero-subtitle {
            font-size: 16px;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 15px;
        }

        .product-image {
            height: 180px;
        }

        .product-info {
            padding: 15px;
        }

        .product-name {
            font-size: 15px;
        }

        .product-price {
            font-size: 18px;
        }
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <h1 class="hero-title">Selamat Datang di T-Store! 🎉</h1>
    <p class="hero-subtitle">Temukan produk elektronik berkualitas dengan harga terbaik</p>
</div>

<!-- Search & Filter -->
<!-- Search & Filter -->
<div class="search-filter-section">
    <form id="searchForm">
        <div class="search-bar">
            <input type="text" 
                   id="searchInput" 
                   name="search" 
                   class="search-input" 
                   placeholder="Cari produk..." 
                   value="{{ request('search') }}">
            <button type="submit" class="search-btn">🔍 Cari</button>
        </div>

        <div class="filters-row">
            <div class="filter-group">
                <label class="filter-label">Kategori</label>
                <select name="category" id="categoryFilter" class="filter-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->products_count }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Harga Minimal</label>
                <input type="number" 
                       name="min_price" 
                       id="minPriceFilter"
                       class="filter-input" 
                       placeholder="Rp 0" 
                       value="{{ request('min_price') }}" 
                       min="0">
            </div>

            <div class="filter-group">
                <label class="filter-label">Harga Maksimal</label>
                <input type="number" 
                       name="max_price" 
                       id="maxPriceFilter"
                       class="filter-input" 
                       placeholder="Rp 999.999.999" 
                       value="{{ request('max_price') }}" 
                       min="0">
            </div>

            <div class="filter-group">
                <label class="filter-label">Urutkan</label>
                <select name="sort" id="sortFilter" class="filter-select">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama (A-Z)</option>
                </select>
            </div>
        </div>
    </form>
</div>

<!-- Search Results Info (akan muncul saat ada pencarian) -->
<div id="searchResultsInfo" style="display: none;"></div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<!-- Featured Products -->
@if($featuredProducts->count() > 0 && !request()->has('search') && !request()->has('category'))
<div style="margin-bottom: 50px;">
    <h2 class="section-title">⭐ Produk Unggulan</h2>
    <div class="products-grid">
        @foreach($featuredProducts as $product)
        <a href="{{ route('product.show', $product->slug) }}" class="product-card" style="text-decoration: none; color: inherit;">
    @if($product->is_featured)
        <span class="featured-badge">⭐ Featured</span>
    @endif
    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="product-image">
    <div class="product-info">
        <div class="product-category">{{ $product->category->name }}</div>
        <div class="product-name">{{ $product->name }}</div>
        <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
        <div class="product-stock {{ $product->stock < 10 ? 'low' : '' }} {{ $product->stock == 0 ? 'out' : '' }}">
            @if($product->stock > 0)
                📦 Stok: {{ $product->stock }}
            @else
                ❌ Stok Habis
            @endif
        </div>
        <div class="product-actions" onclick="event.preventDefault(); event.stopPropagation();">
            <button class="btn-add-cart" {{ $product->stock == 0 ? 'disabled' : '' }} 
        onclick="addToCartFromHome({{ $product->id }}, '{{ $product->name }}')">
    🛒 Tambah ke Keranjang
</button>
            <button class="btn-wishlist" onclick="addToWishlistFromHome({{ $product->id }}, '{{ $product->name }}')">❤️</button>
        </div>
    </div>
</a>
        @endforeach
    </div>
</div>
@endif

<!-- All Products -->
<h2 class="section-title">📦 Semua Produk</h2>

<div id="productsContainer">
    @if($products->count() > 0)
        <div class="products-grid">
            @foreach($products as $product)
            <a href="{{ route('product.show', $product->slug) }}" class="product-card" style="text-decoration: none; color: inherit;">
                @if($product->is_featured)
                    <span class="featured-badge">⭐ Featured</span>
                @endif
                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="product-image">
                <div class="product-info">
                    <div class="product-category">{{ $product->category->name }}</div>
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    <div class="product-stock {{ $product->stock < 10 ? 'low' : '' }} {{ $product->stock == 0 ? 'out' : '' }}">
                        @if($product->stock > 0)
                            📦 Stok: {{ $product->stock }}
                        @else
                            ❌ Stok Habis
                        @endif
                    </div>
                    <div class="product-actions" onclick="event.preventDefault(); event.stopPropagation();">
                        <button class="btn-add-cart" {{ $product->stock == 0 ? 'disabled' : '' }} 
        onclick="addToCartFromHome({{ $product->id }}, '{{ $product->name }}')">
    🛒 Tambah ke Keranjang
</button>
                        <button class="btn-wishlist" onclick="addToWishlistFromHome({{ $product->id }}, '{{ $product->name }}')">❤️</button>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination">
            {{ $products->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="icon">🔍</div>
            <h3>Produk Tidak Ditemukan</h3>
            <p>Coba ubah filter atau kata kunci pencarian Anda</p>
        </div>
    @endif
</div>

@push('scripts')

<script>

function addToWishlistFromHome(productId, productName) {
    fetch('{{ route('wishlist.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => {
        // ✅ TAMBAHAN: Cek status 401 untuk guest
        if (response.status === 401) {
            showToast('Silakan login terlebih dahulu untuk menambahkan ke wishlist', 'error', 2000);
            setTimeout(() => {
                window.location.href = '{{ route('login') }}?redirect={{ url()->current() }}';
            }, 1000);
            return null;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            @auth
            updateWishlistBadge(data.wishlist_count, true);
            @endauth
            showToast(`${productName} berhasil ditambahkan ke wishlist!`, 'success');
        } else if (data && !data.success) {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'error');
    });
}

// Add to Cart from Home
function addToCartFromHome(productId, productName) {
    fetch('{{ route('cart.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => {
        // ✅ TAMBAHAN: Cek status 401 untuk guest
        if (response.status === 401) {
            showToast('Silakan login terlebih dahulu untuk menambahkan ke keranjang', 'error', 2000);
            setTimeout(() => {
                window.location.href = '{{ route('login') }}?redirect={{ url()->current() }}';
            }, 1000);
            return null;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            @auth
            updateCartBadge(data.cart_count, true);
            @endauth
            showToast(`${productName} berhasil ditambahkan ke keranjang!`, 'success');
        } else if (data && !data.success) {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'error');
    });
}
    // Debounce function untuk performance
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // AJAX Search Function
    function performSearch() {
        const formData = {
            search: document.getElementById('searchInput').value,
            category: document.getElementById('categoryFilter').value,
            min_price: document.getElementById('minPriceFilter').value,
            max_price: document.getElementById('maxPriceFilter').value,
            sort: document.getElementById('sortFilter').value,
        };

        // Show loading
        document.getElementById('loadingOverlay').classList.add('active');

        // Build query string
        const queryString = new URLSearchParams(formData).toString();

        // AJAX Request
        fetch(`{{ route('search') }}?${queryString}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            // Hide loading
            document.getElementById('loadingOverlay').classList.remove('active');

            // Update products container
            updateProductsContainer(data.products);

            // Show search results info
            if (formData.search || formData.category || formData.min_price || formData.max_price) {
                showSearchResultsInfo(data.count, formData.search);
            } else {
                hideSearchResultsInfo();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loadingOverlay').classList.remove('active');
            alert('Terjadi kesalahan saat mencari produk');
        });
    }

    // Update Products Container
   function updateProductsContainer(products) {
    const container = document.getElementById('productsContainer');

    if (products.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="icon">🔍</div>
                <h3>Produk Tidak Ditemukan</h3>
                <p>Coba ubah filter atau kata kunci pencarian Anda</p>
            </div>
        `;
        return;
    }

    let html = '<div class="products-grid">';
    
    products.forEach(product => {
        const stockClass = product.stock < 10 ? 'low' : (product.stock == 0 ? 'out' : '');
        const stockText = product.stock > 0 ? `📦 Stok: ${product.stock}` : '❌ Stok Habis';
        const disabledBtn = product.stock == 0 ? 'disabled' : '';
        const featuredBadge = product.is_featured ? '<span class="featured-badge">⭐ Featured</span>' : '';

        html += `
            <a href="/product/${product.slug}" class="product-card" style="text-decoration: none; color: inherit;">
                ${featuredBadge}
                <img src="/storage/${product.main_image}" alt="${product.name}" class="product-image">
                <div class="product-info">
                    <div class="product-category">${product.category.name}</div>
                    <div class="product-name">${product.name}</div>
                    <div class="product-price">Rp ${formatNumber(product.price)}</div>
                    <div class="product-stock ${stockClass}">${stockText}</div>
                    <div class="product-actions" onclick="event.preventDefault(); event.stopPropagation();">
                        <button class="btn-add-cart" ${disabledBtn} onclick="addToCartFromHome(${product.id}, '${product.name}')">
                            🛒 Tambah ke Keranjang
                        </button>
                        <button class="btn-wishlist" onclick="addToWishlistFromHome(${product.id}, '${product.name}')">❤️</button>
                    </div>
                </div>
            </a>
        `;
    });

    html += '</div>';
    container.innerHTML = html;
}

    // Show Search Results Info
    function showSearchResultsInfo(count, searchTerm) {
        const infoDiv = document.getElementById('searchResultsInfo');
        let message = `Menampilkan ${count} produk`;
        
        if (searchTerm) {
            message += ` untuk "<strong>${searchTerm}</strong>"`;
        }

        infoDiv.innerHTML = `<div class="search-results-info">${message}</div>`;
        infoDiv.style.display = 'block';
    }

    // Hide Search Results Info
    function hideSearchResultsInfo() {
        document.getElementById('searchResultsInfo').style.display = 'none';
    }

    // Format Number
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const minPriceFilter = document.getElementById('minPriceFilter');
        const maxPriceFilter = document.getElementById('maxPriceFilter');
        const sortFilter = document.getElementById('sortFilter');

        // Form submit
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch();
        });

        // Real-time search dengan debounce
        searchInput.addEventListener('input', debounce(function() {
            performSearch();
        }, 500));

        // Filter changes
        categoryFilter.addEventListener('change', performSearch);
        sortFilter.addEventListener('change', performSearch);

        // Price filter dengan debounce
        minPriceFilter.addEventListener('input', debounce(performSearch, 800));
        maxPriceFilter.addEventListener('input', debounce(performSearch, 800));
    });
</script>
@endpush

@endsection