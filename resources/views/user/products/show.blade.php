{{-- resources/views/user/products/show.blade.php --}}
@extends('user.layouts.app')

@section('title', $product->name)

@section('content')
<div class="product-detail-container">
    {{-- Product Detail Content --}}
    <div class="product-grid">
        <a href="{{ route('home') }}" class="back">
    <img src="/Images/button/back.png">
</a>
        <div class="product-image">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
        </div>
        
        <div class="product-info">
            <h1>{{ $product->name }}</h1>
            <p class="category">{{ $product->category->name }}</p>
            
            <div class="price">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>
            
            <div class="stock-info">
                @if($product->stock > 0)
                    <span class="in-stock">✓ Stok Tersedia ({{ $product->stock }})</span>
                @else
                    <span class="out-of-stock">✗ Stok Habis</span>
                @endif
            </div>
            
            <div class="description">
                <h3>Deskripsi Produk</h3>
                <p>{{ $product->description }}</p>
            </div>
            
            {{-- Quantity & Add to Cart --}}
            @if($product->stock > 0)
                <div class="add-to-cart-section">
                    <div class="quantity-selector">
                        <button onclick="decrementQty()" class="qty-btn">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}" readonly>
                        <button onclick="incrementQty()" class="qty-btn">+</button>
                    </div>
                    
                    {{-- Universal Buttons for Both Guest & Auth --}}
                    <button onclick="addToCart()" class="btn-add-cart" id="addToCartBtn">
                        🛒 Tambah ke Keranjang
                    </button>
                    
                    <button onclick="addToWishlist()" class="btn-wishlist" id="wishlistBtn">
                        ❤️ Wishlist
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .back {
            width: 25px;
    
            cursor: pointer;
            transition: transform 0.2s;
        }
        .back:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .back img{
            width: 20px;
            height: 20px;
            border-radius: 5%;
            background= transparent;
        }
    .product-detail-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .product-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        background: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .product-image img {
        width: 100%;
        border-radius: 12px;
    }
    
    .product-info h1 {
        font-size: 32px;
        margin-bottom: 10px;
    }
    
    .category {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 20px;
    }
    
    .price {
        font-size: 36px;
        font-weight: 700;
        color: #27ae60;
        margin: 20px 0;
    }
    
    .stock-info {
        margin: 20px 0;
    }
    
    .in-stock {
        color: #27ae60;
        font-weight: 600;
    }
    
    .out-of-stock {
        color: #e74c3c;
        font-weight: 600;
    }
    
    .description {
        margin: 30px 0;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .description h3 {
        margin-bottom: 10px;
    }
    
    .add-to-cart-section {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        flex-wrap: wrap;
    }
    
    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8f9fa;
        padding: 5px;
        border-radius: 8px;
    }
    
    .qty-btn {
        width: 40px;
        height: 40px;
        border: none;
        background: white;
        border-radius: 6px;
        cursor: pointer;
        font-size: 18px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .qty-btn:hover {
        background: #667eea;
        color: white;
    }
    
    #quantity {
        width: 60px;
        text-align: center;
        border: none;
        background: transparent;
        font-size: 18px;
        font-weight: 600;
    }
    
    .btn-add-cart, .btn-wishlist {
        flex: 1;
        padding: 15px 30px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-add-cart {
        background: #667eea;
        color: white;
    }
    
    .btn-add-cart:hover {
        background: #5568d3;
        transform: translateY(-2px);
    }
    
    .btn-wishlist {
        background: white;
        color: #e74c3c;
        border: 2px solid #e74c3c;
    }
    
    .btn-wishlist:hover {
        background: #e74c3c;
        color: white;
    }
    
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: 1fr;
            padding: 20px;
        }
        
        .add-to-cart-section {
            flex-direction: column;
        }
        
        .btn-add-cart, .btn-wishlist {
            width: 100%;
        }
    }
</style>

@push('scripts')
<script>
    const maxStock = {{ $product->stock }};
    
    function incrementQty() {
        const qtyInput = document.getElementById('quantity');
        let currentQty = parseInt(qtyInput.value);
        if (currentQty < maxStock) {
            qtyInput.value = currentQty + 1;
        }
    }
    
    function decrementQty() {
        const qtyInput = document.getElementById('quantity');
        let currentQty = parseInt(qtyInput.value);
        if (currentQty > 1) {
            qtyInput.value = currentQty - 1;
        }
    }
    
    // Universal Add to Cart Function (Works for both Guest & Auth)
    function addToCart() {
        const quantity = parseInt(document.getElementById('quantity').value);
        const btn = document.getElementById('addToCartBtn');
        
        btn.disabled = true;
        btn.textContent = '⏳ Menambahkan...';
        
        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: {{ $product->id }},
                quantity: quantity
            })
        })
        .then(response => {
            // Check if unauthenticated (401)
            if (response.status === 401) {
                showToast('Silakan login terlebih dahulu untuk menambahkan ke keranjang', 'error', 2000);
                setTimeout(() => {
                    window.location.href = '{{ route('login') }}?redirect={{ url()->current() }}';
                }, 1500);
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                showToast(data.message, 'success');
                @auth
                updateCartBadge(data.cart_count);
                @endauth
                document.getElementById('quantity').value = 1;
            } else if (data && !data.success) {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = '🛒 Tambah ke Keranjang';
        });
    }
    
    // Universal Add to Wishlist Function (Works for both Guest & Auth)
    function addToWishlist() {
        const btn = document.getElementById('wishlistBtn');
        
        btn.disabled = true;
        btn.textContent = '⏳ Menambahkan...';
        
        fetch('{{ route('wishlist.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: {{ $product->id }}
            })
        })
        .then(response => {
            // Check if unauthenticated (401)
            if (response.status === 401) {
                showToast('Silakan login terlebih dahulu untuk menambahkan ke wishlist', 'error', 2000);
                setTimeout(() => {
                    window.location.href = '{{ route('login') }}?redirect={{ url()->current() }}';
                }, 1500);
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                showToast(data.message, 'success');
                @auth
                updateWishlistBadge(data.wishlist_count);
                @endauth
            } else if (data && !data.success) {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = '❤️ Wishlist';
        });
    }
</script>
@endpush
@endsection