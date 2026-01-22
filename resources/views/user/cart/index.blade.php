@extends('user.layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<style>
    .cart-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin-top: 20px;
    }

    .cart-items-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
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

    .cart-item {
        display: flex;
        gap: 20px;
        padding: 20px;
        border-bottom: 2px solid #e9ecef;
        transition: background 0.3s;
    }

    .cart-item:hover {
        background: #f8f9fa;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .item-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
        flex-shrink: 0;
    }

    .item-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .item-name {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .item-category {
        font-size: 13px;
        color: #7f8c8d;
        text-transform: uppercase;
    }

    .item-price {
        font-size: 20px;
        font-weight: 700;
        color: #e74c3c;
    }

    .item-actions {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-direction: column;
        justify-content: space-between;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f8f9fa;
        padding: 8px 15px;
        border-radius: 10px;
    }

    .quantity-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: white;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        color: #667eea;
    }

    .quantity-btn:hover:not(:disabled) {
        background: #667eea;
        color: white;
    }

    .quantity-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .quantity-value {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        min-width: 30px;
        text-align: center;
    }

    .item-subtotal {
        font-size: 18px;
        font-weight: 700;
        color: #27ae60;
        text-align: right;
    }

    .btn-remove {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-remove:hover {
        background: #c0392b;
        transform: translateY(-2px);
    }

    /* Summary Section */
    .cart-summary {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        position: sticky;
        top: 100px;
        height: fit-content;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: #7f8c8d;
        font-size: 15px;
    }

    .summary-value {
        color: #2c3e50;
        font-weight: 600;
        font-size: 15px;
    }

    .total-row {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid #e9ecef;
    }

    .total-row .summary-label {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
    }

    .total-row .summary-value {
        font-size: 24px;
        color: #27ae60;
    }

    .btn-checkout {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 20px;
    }

    .btn-checkout:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
         color: white;
    }

    .btn-continue {
        width: 100%;
        padding: 14px;
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 10px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-continue:hover {
        background: #f0f3ff;
    }

    .empty-cart {
        text-align: center;
        padding: 80px 20px;
        color: #7f8c8d;
    }

    .empty-cart .icon {
        font-size: 80px;
        margin-bottom: 20px;
    }

    .max-items-warning {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #856404;
    }

    @media (max-width: 768px) {
        .cart-container {
            grid-template-columns: 1fr;
        }

        .cart-item {
            flex-direction: column;
        }

        .item-actions {
            flex-direction: row;
            width: 100%;
            justify-content: space-between;
        }

        .cart-summary {
            position: static;
        }
    }
</style>

<h2 class="section-title">🛒 Keranjang Belanja</h2>

@php
    $totalItems = $cartItems->sum('quantity');
@endphp

@if($totalItems >= 20)
<div class="max-items-warning">
    ⚠️ <strong>Perhatian:</strong> Anda memiliki {{ $totalItems }} item di keranjang. Maksimal 25 item.
</div>
@endif

<div class="cart-container">
    <!-- Cart Items -->
    <div class="cart-items-section">
        @if($cartItems->count() > 0)
            <div id="cartItemsContainer">
                @foreach($cartItems as $item)
                <div class="cart-item" id="cart-item-{{ $item->id }}">
                    @if($item->product)
                        <a href="{{ route('product.show', $item->product->slug) }}">
                            <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product->name }}" class="item-image">
                        </a>
                    @else
                        <div class="item-image" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center; font-size: 40px;">
                            📦
                        </div>
                    @endif

                    <div class="item-details">
                        <div class="item-category">{{ $item->product->category->name ?? '-' }}</div>
                        <div class="item-name">{{ $item->product_name ?? $item->product->name }}</div>
                        <div class="item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        @if($item->product && $item->quantity > $item->product->stock)
                            <div style="color: #e74c3c; font-size: 13px; font-weight: 600;">
                                ⚠️ Stok hanya tersedia {{ $item->product->stock }}
                            </div>
                        @endif
                    </div>

                    <div class="item-actions">
    <div class="quantity-control">
        <button class="quantity-btn" 
                onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" 
                {{ $item->quantity <= 1 ? 'disabled' : '' }} 
                id="btnDecrease-{{ $item->id }}">
            -
        </button>
        <span class="quantity-value" id="quantity-{{ $item->id }}">{{ $item->quantity }}</span>
        <button class="quantity-btn" 
                onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" 
                {{ $item->product && $item->quantity >= $item->product->stock ? 'disabled' : '' }}
                id="btnIncrease-{{ $item->id }}">
            +
        </button>
    </div>

    <div class="item-subtotal" id="subtotal-{{ $item->id }}">
        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
    </div>

    <button class="btn-remove" 
            onclick="removeItem({{ $item->id }})" 
            id="btnRemove-{{ $item->id }}">
        🗑️ Hapus
    </button>
</div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-cart">
                <div class="icon">🛒</div>
                <h3>Keranjang Anda Kosong</h3>
                <p>Belum ada produk di keranjang belanja Anda</p>
                <a href="{{ route('home') }}" class="btn-checkout" style="max-width: 300px; margin: 20px auto 0;">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>

    <!-- Cart Summary -->
    @if($cartItems->count() > 0)
    <div class="cart-summary">
        <h3 style="margin-bottom: 20px; color: #2c3e50;">📊 Ringkasan Belanja</h3>

        <div class="summary-row">
            <span class="summary-label">Total Item</span>
            <span class="summary-value" id="totalItemsDisplay">{{ $totalItems }} item</span>
        </div>

        <div class="summary-row total-row">
            <span class="summary-label">TOTAL</span>
            <span class="summary-value" id="cartTotalDisplay">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>

       <a href="{{ route('checkout.index') }}" class="btn-checkout" style="text-decoration: none; display: block; text-align: center;">
    Lanjut ke Checkout
</a>
        <a href="{{ route('home') }}" class="btn-continue">
            ← Lanjut Belanja
        </a>
    </div>
    @endif
</div>

@push('scripts')
<script>
    console.log('Cart script loaded'); // Debug

    // Update Quantity
    function updateQuantity(cartId, newQuantity) {
        console.log('updateQuantity called:', cartId, newQuantity); // Debug
        
        if (newQuantity < 1) {
            console.log('Quantity less than 1, returning');
            return;
        }

        // Show loading
        const qtyElement = document.getElementById(`quantity-${cartId}`);
        const originalQty = qtyElement.textContent;
        qtyElement.textContent = '...';

        fetch(`/cart/${cartId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ 
                quantity: newQuantity 
            })
        })
        .then(response => {
            console.log('Response status:', response.status); // Debug
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data); // Debug
            
            if (data.success) {
                // Update quantity display
                document.getElementById(`quantity-${cartId}`).textContent = newQuantity;
                
                // Update subtotal
                document.getElementById(`subtotal-${cartId}`).textContent = 
                    'Rp ' + formatNumber(data.subtotal);
                
                // Update cart total
                document.getElementById('cartTotalDisplay').textContent = 
                    'Rp ' + formatNumber(data.cart_total);

                // Update total items count
                updateTotalItemsCount();

                // Show success toast
                if (typeof showToast === 'function') {
                    showToast(data.message, 'success');
                } else {
                    alert(data.message);
                }
            } else {
                // Revert quantity on error
                document.getElementById(`quantity-${cartId}`).textContent = originalQty;
                
                if (typeof showToast === 'function') {
                    showToast(data.message, 'error');
                } else {
                    alert(data.message);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error); // Debug
            
            // Revert quantity on error
            document.getElementById(`quantity-${cartId}`).textContent = originalQty;
            
            if (typeof showToast === 'function') {
                showToast('Terjadi kesalahan: ' + error.message, 'error');
            } else {
                alert('Terjadi kesalahan: ' + error.message);
            }
        });
    }

    // Remove Item
    function removeItem(cartId) {
        console.log('removeItem called:', cartId); // Debug
        
        if (!confirm('Yakin ingin menghapus produk ini dari keranjang?')) {
            return;
        }

        // Show loading
        const item = document.getElementById(`cart-item-${cartId}`);
        item.style.opacity = '0.5';

        fetch(`/cart/${cartId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            console.log('Delete response status:', response.status); // Debug
            return response.json();
        })
        .then(data => {
            console.log('Delete response data:', data); // Debug
            
            if (data.success) {
                // Animate removal
                item.style.transition = 'all 0.3s ease';
                item.style.opacity = '0';
                item.style.transform = 'translateX(-50px)';
                
                setTimeout(() => {
                    item.remove();
                    
                    // Check if cart is empty
                    const remainingItems = document.querySelectorAll('[id^="cart-item-"]');
                    console.log('Remaining items:', remainingItems.length); // Debug
                    
                    if (remainingItems.length === 0) {
                        console.log('Cart is empty, reloading...'); // Debug
                        location.reload();
                    } else {
                        // Update totals
                        document.getElementById('cartTotalDisplay').textContent = 
                            'Rp ' + formatNumber(data.cart_total);
                        
                        updateTotalItemsCount();
                    }
                }, 300);

                // Update cart badge
                if (typeof updateCartBadge === 'function') {
                    updateCartBadge(data.cart_count, true);
                }

                // Show success toast
                if (typeof showToast === 'function') {
                    showToast(data.message, 'success');
                } else {
                    alert(data.message);
                }
            } else {
                // Revert opacity on error
                item.style.opacity = '1';
                
                if (typeof showToast === 'function') {
                    showToast(data.message, 'error');
                } else {
                    alert(data.message);
                }
            }
        })
        .catch(error => {
            console.error('Delete error:', error); // Debug
            
            // Revert opacity on error
            item.style.opacity = '1';
            
            if (typeof showToast === 'function') {
                showToast('Terjadi kesalahan: ' + error.message, 'error');
            } else {
                alert('Terjadi kesalahan: ' + error.message);
            }
        });
    }

    // Update Total Items Count
    function updateTotalItemsCount() {
        const quantities = document.querySelectorAll('[id^="quantity-"]');
        let total = 0;
        
        quantities.forEach(el => {
            const qty = parseInt(el.textContent);
            if (!isNaN(qty)) {
                total += qty;
            }
        });
        
        const totalItemsElement = document.getElementById('totalItemsDisplay');
        if (totalItemsElement) {
            totalItemsElement.textContent = total + ' item';
        }
        
        console.log('Total items updated:', total); // Debug
    }

    // Format Number Helper
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    // Test on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded');
        console.log('CSRF Token:', '{{ csrf_token() }}');
        
        // Test buttons
        const qtyBtns = document.querySelectorAll('.quantity-btn');
        console.log('Quantity buttons found:', qtyBtns.length);
        
        const removeBtns = document.querySelectorAll('.btn-remove');
        console.log('Remove buttons found:', removeBtns.length);
    });
</script>
@endpush
@stack('scripts')
@endsection