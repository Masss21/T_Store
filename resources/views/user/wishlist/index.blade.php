@extends('user.layouts.app')

@section('title', 'Wishlist Saya')

@section('content')
<style>
    .wishlist-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .wishlist-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
        position: relative;
    }

    .wishlist-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .wishlist-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        background: #f8f9fa;
    }

    .wishlist-info {
        padding: 20px;
    }

    .wishlist-category {
        font-size: 12px;
        color: #7f8c8d;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .wishlist-name {
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

    .wishlist-price {
        font-size: 24px;
        font-weight: 700;
        color: #e74c3c;
        margin-bottom: 15px;
    }

    .wishlist-stock {
        font-size: 13px;
        margin-bottom: 15px;
    }

    .stock-available {
        color: #27ae60;
    }

    .stock-out {
        color: #e74c3c;
    }

    .wishlist-actions {
        display: flex;
        gap: 10px;
    }

    .btn-move-cart {
        flex: 1;
        padding: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
    }

    .btn-move-cart:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-move-cart:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-remove-wishlist {
        padding: 12px 16px;
        background: white;
        border: 2px solid #e74c3c;
        color: #e74c3c;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 18px;
    }

    .btn-remove-wishlist:hover {
        background: #e74c3c;
        color: white;
    }

    .remove-icon {
        position: absolute;
        top: 15px;
        right: 15px;
        background: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: all 0.3s;
        z-index: 10;
    }

    .remove-icon:hover {
        background: #e74c3c;
        color: white;
        transform: scale(1.1);
    }

    .empty-wishlist {
        text-align: center;
        padding: 80px 20px;
        color: #7f8c8d;
    }

    .empty-wishlist .icon {
        font-size: 80px;
        margin-bottom: 20px;
    }

    .btn-shop {
        padding: 14px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        margin-top: 20px;
    }

    .btn-shop:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    @media (max-width: 768px) {
        .wishlist-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 15px;
        }

        .wishlist-image {
            height: 180px;
        }

        .wishlist-info {
            padding: 15px;
        }
    }
</style>

<div class="wishlist-header">
    <h2 class="section-title">❤️ Wishlist Saya</h2>
    <span style="color: #7f8c8d; font-size: 15px;">{{ $wishlistItems->count() }} produk</span>
</div>

@if($wishlistItems->count() > 0)
    <div class="wishlist-grid">
        @foreach($wishlistItems as $item)
        <div class="wishlist-card" id="wishlist-item-{{ $item->id }}">
            <div class="remove-icon" onclick="removeFromWishlist({{ $item->id }})" title="Hapus dari wishlist">
                ✕
            </div>

            @if($item->product)
                <a href="{{ route('product.show', $item->product->slug) }}">
                    <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product->name }}" class="wishlist-image">
                </a>

                <div class="wishlist-info">
                    <div class="wishlist-category">{{ $item->product->category->name ?? '-' }}</div>
                    <div class="wishlist-name">{{ $item->product->name }}</div>
                    <div class="wishlist-price">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
                    
                    <div class="wishlist-stock {{ $item->product->stock > 0 ? 'stock-available' : 'stock-out' }}">
                        @if($item->product->stock > 0)
                            📦 Stok: {{ $item->product->stock }}
                        @else
                            ❌ Stok Habis
                        @endif
                    </div>

                    <div class="wishlist-actions">
                        <button class="btn-move-cart" 
                                onclick="moveToCart({{ $item->id }}, '{{ $item->product->name }}')"
                                {{ $item->product->stock == 0 ? 'disabled' : '' }}>
                            🛒 Pindah ke Keranjang
                        </button>
                    </div>
                </div>
            @else
                <div style="padding: 20px; text-align: center; color: #e74c3c;">
                    Produk tidak tersedia
                </div>
            @endif
        </div>
        @endforeach
    </div>
@else
    <div class="empty-wishlist">
        <div class="icon">❤️</div>
        <h3>Wishlist Anda Kosong</h3>
        <p>Belum ada produk yang Anda sukai</p>
        <a href="{{ route('home') }}" class="btn-shop">Mulai Belanja</a>
    </div>
@endif

<script>
    // Remove from Wishlist
    function removeFromWishlist(wishlistId) {
        fetch(`/wishlist/${wishlistId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove item from DOM
                const item = document.getElementById(`wishlist-item-${wishlistId}`);
                item.style.transition = 'all 0.3s ease';
                item.style.opacity = '0';
                item.style.transform = 'scale(0.9)';
                
                setTimeout(() => {
                    item.remove();
                    
                    // Check if wishlist is empty
                    const grid = document.querySelector('.wishlist-grid');
                    if (grid && grid.children.length === 0) {
                        location.reload();
                    }
                }, 300);

                // Update wishlist count in navbar (will be added)
                if (typeof updateWishlistBadge === 'function') {
                    updateWishlistBadge(data.wishlist_count, true);
                }

                showToast(data.message, 'success');
} else {
showToast(data.message, 'error');
}
})
.catch(error => {
console.error('Error:', error);
showToast('Terjadi kesalahan', 'error');
});
}
// Move to Cart
function moveToCart(wishlistId, productName) {
    fetch(`/wishlist/${wishlistId}/move-to-cart`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove item from DOM
            const item = document.getElementById(`wishlist-item-${wishlistId}`);
            item.style.transition = 'all 0.3s ease';
            item.style.opacity = '0';
            item.style.transform = 'translateX(-100%)';
            
            setTimeout(() => {
                item.remove();
                
                // Check if wishlist is empty
                const grid = document.querySelector('.wishlist-grid');
                if (grid && grid.children.length === 0) {
                    location.reload();
                }
            }, 300);

            // Update badges
            if (typeof updateWishlistBadge === 'function') {
                updateWishlistBadge(data.wishlist_count, true);
            }
            updateCartBadge(data.cart_count, true);

            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'error');
    });
}

</script>
@endsection