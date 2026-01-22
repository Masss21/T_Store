@extends('user.layouts.app')

@section('title', $product->name)

@section('content')
<style>
    .breadcrumb {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        font-size: 14px;
        color: #7f8c8d;
    }

    .breadcrumb a {
        color: #3498db;
        text-decoration: none;
        transition: color 0.3s;
    }

    .breadcrumb a:hover {
        color: #2980b9;
    }

    .product-container {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 40px;
    }

    .product-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
    }

    /* Image Gallery */
    .gallery-section {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .main-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 15px;
        background: #f8f9fa;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .thumbnail-gallery {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        padding: 5px;
    }

    .thumbnail {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.3s;
        flex-shrink: 0;
    }

    .thumbnail:hover {
        border-color: #667eea;
        transform: scale(1.05);
    }

    .thumbnail.active {
        border-color: #667eea;
        box-shadow: 0 0 10px rgba(102, 126, 234, 0.3);
    }

    /* Product Info */
    .product-info-section {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .product-category-badge {
        display: inline-block;
        background: #ecf0f1;
        color: #2c3e50;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .product-title {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1.3;
    }

    .product-meta {
        display: flex;
        gap: 20px;
        font-size: 14px;
        color: #7f8c8d;
    }

    .product-price-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        margin: 20px 0;
    }

    .product-price {
        font-size: 42px;
        font-weight: 700;
        color: #e74c3c;
        margin-bottom: 10px;
    }

    .stock-info {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 600;
    }

    .stock-available {
        color: #27ae60;
    }

    .stock-low {
        color: #f39c12;
    }

    .stock-out {
        color: #e74c3c;
    }

    .product-description {
        line-height: 1.8;
        color: #495057;
        padding: 20px 0;
        border-top: 2px solid #e9ecef;
        border-bottom: 2px solid #e9ecef;
    }

    /* Quantity Selector */
    .quantity-section {
        display: flex;
        align-items: center;
        gap: 15px;
        margin: 20px 0;
    }

    .quantity-label {
        font-weight: 600;
        color: #2c3e50;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 15px;
        background: #f8f9fa;
        padding: 10px 20px;
        border-radius: 10px;
    }

    .quantity-btn {
        width: 35px;
        height: 35px;
        border: none;
        background: white;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        color: #667eea;
    }

    .quantity-btn:hover {
        background: #667eea;
        color: white;
        transform: scale(1.1);
    }

    .quantity-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .quantity-value {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        min-width: 40px;
        text-align: center;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 15px;
    }

    .btn-add-cart {
        flex: 1;
        padding: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-add-cart:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-add-cart:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-wishlist {
        padding: 16px 20px;
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-size: 24px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-wishlist:hover {
        background: #e74c3c;
        border-color: #e74c3c;
        color: white;
        transform: scale(1.1);
    }

    /* Specifications */
    .specifications-section {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 40px;
    }

    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .specs-table {
        width: 100%;
    }

    .specs-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        padding: 18px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .specs-row:last-child {
        border-bottom: none;
    }

    .specs-label {
        font-weight: 600;
        color: #7f8c8d;
    }

    .specs-value {
        color: #2c3e50;
    }

    /* Related Products */
    .related-products-section {
        margin-top: 60px;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 25px;
    }

    .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    .product-card-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f8f9fa;
    }

    .product-card-info {
        padding: 20px;
    }

    .product-card-name {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-card-price {
        font-size: 20px;
        font-weight: 700;
        color: #e74c3c;
    }

    @media (max-width: 768px) {
        .product-layout {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .main-image {
            height: 350px;
        }

        .product-title {
            font-size: 24px;
        }

        .product-price {
            font-size: 32px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .specs-row {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .related-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        }
    }
</style>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('home') }}">Home</a>
    <span>/</span>
    <a href="{{ route('home') }}?category={{ $product->category_id }}">{{ $product->category->name }}</a>
    <span>/</span>
    <span>{{ $product->name }}</span>
</div>

<!-- Product Container -->
<div class="product-container">
    <div class="product-layout">
        <!-- Left: Image Gallery -->
        <div class="gallery-section">
            <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="main-image" id="mainImage">
            
            @if($product->gallery_images && count($product->gallery_images) > 0)
            <div class="thumbnail-gallery">
                <img src="{{ asset('storage/' . $product->main_image) }}" class="thumbnail active" onclick="changeImage('{{ asset('storage/' . $product->main_image) }}', this)">
                @foreach($product->gallery_images as $image)
                    <img src="{{ asset('storage/' . $image) }}" class="thumbnail" onclick="changeImage('{{ asset('storage/' . $image) }}', this)">
                @endforeach
            </div>
            @endif
        </div>

        <!-- Right: Product Info -->
        <div class="product-info-section">
            <div>
                <span class="product-category-badge">{{ $product->category->name }}</span>
            </div>

            <h1 class="product-title">{{ $product->name }}</h1>

            <div class="product-meta">
                <span>👁️ {{ $product->views }} dilihat</span>
                @if($product->is_featured)
                    <span>⭐ Produk Unggulan</span>
                @endif
            </div>

            <div class="product-price-section">
                <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                <div class="stock-info">
                    @if($product->stock > 10)
                        <span class="stock-available">✓ Stok Tersedia ({{ $product->stock }})</span>
                    @elseif($product->stock > 0)
                        <span class="stock-low">⚠️ Stok Terbatas ({{ $product->stock }})</span>
                    @else
                        <span class="stock-out">✗ Stok Habis</span>
                    @endif
                </div>
            </div>

            <div class="product-description">
                {{ $product->description }}
            </div>

            @if($product->stock > 0)
            <!-- Quantity Selector -->
            <div class="quantity-section">
                <span class="quantity-label">Jumlah:</span>
                <div class="quantity-control">
                    <button class="quantity-btn" onclick="decreaseQuantity()" id="btnDecrease">-</button>
                    <span class="quantity-value" id="quantityValue">1</span>
                    <button class="quantity-btn" onclick="increaseQuantity()" id="btnIncrease">+</button>
                </div>
                <span style="color: #7f8c8d; font-size: 14px;">Max: {{ $product->stock }}</span>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn-add-cart" onclick="addToCart()">
                    <span style="font-size: 20px;">🛒</span>
                    <span>Tambah ke Keranjang</span>
                </button>
                <button class="btn-wishlist" onclick="addToWishlist()">❤️</button>
            </div>
            @else
            <div style="padding: 20px; background: #fee; border-radius: 10px; text-align: center; color: #c33; font-weight: 600;">
                ❌ Produk ini sedang tidak tersedia
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Specifications -->
@if($product->specifications && count($product->specifications) > 0)
<div class="specifications-section">
    <h2 class="section-title">📋 Spesifikasi Produk</h2>
    <div class="specs-table">
        @foreach($product->specifications as $key => $value)
        <div class="specs-row">
            <div class="specs-label">{{ $key }}</div>
            <div class="specs-value">{{ $value }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<div class="related-products-section">
    <h2 class="section-title">🔗 Produk Terkait</h2>
    <div class="related-grid">
        @foreach($relatedProducts as $related)
        <a href="{{ route('product.show', $related->slug) }}" class="product-card">
            <img src="{{ asset('storage/' . $related->main_image) }}" alt="{{ $related->name }}" class="product-card-image">
            <div class="product-card-info">
                <div class="product-card-name">{{ $related->name }}</div>
                <div class="product-card-price">Rp {{ number_format($related->price, 0, ',', '.') }}</div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

<script>
    const maxStock = {{ $product->stock }};
    let quantity = 1;

    function changeImage(src, element) {
        document.getElementById('mainImage').src = src;
        
        // Remove active class from all thumbnails
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        
        // Add active class to clicked thumbnail
        element.classList.add('active');
    }

    function updateQuantity() {
        document.getElementById('quantityValue').textContent = quantity;
        document.getElementById('btnDecrease').disabled = quantity <= 1;
        document.getElementById('btnIncrease').disabled = quantity >= maxStock;
    }

    function increaseQuantity() {
        if (quantity < maxStock) {
            quantity++;
            updateQuantity();
        }
    }

    function decreaseQuantity() {
        if (quantity > 1) {
            quantity--;
            updateQuantity();
        }
    }

function addToCart() {
    const productId = {{ $product->id }};
    const productName = "{{ $product->name }}";

    fetch('{{ route('cart.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart badge with animation
            updateCartBadge(data.cart_count, true);

            // Show toast notification
            showToast(`${productName} berhasil ditambahkan ke keranjang!`, 'success');
            
            // Reset quantity to 1
            quantity = 1;
            updateQuantity();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat menambahkan ke keranjang', 'error');
    });
}

    function addToWishlist() {
    const productId = {{ $product->id }};
    const productName = "{{ $product->name }}";

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
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update wishlist badge
            updateWishlistBadge(data.wishlist_count, true);

            showToast(`${productName} berhasil ditambahkan ke wishlist!`, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'error');
    });
}

    // Initialize
    updateQuantity();
</script>

@endsection