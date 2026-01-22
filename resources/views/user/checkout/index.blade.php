@extends('user.layouts.app')

@section('title', 'Checkout')

@section('content')
<style>
    .checkout-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin-top: 20px;
    }

    .checkout-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 25px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #2c3e50;
        font-weight: 600;
        font-size: 14px;
    }

    .form-input,
    .form-textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .shipping-methods,
    .payment-methods {
        display: grid;
        gap: 15px;
    }

    .method-option {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .method-option:hover {
        border-color: #667eea;
        background: #f8f9ff;
    }

    .method-option.selected {
        border-color: #667eea;
        background: #f8f9ff;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .method-radio {
        width: 20px;
        height: 20px;
        accent-color: #667eea;
    }

    .method-info {
        flex: 1;
    }

    .method-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .method-detail {
        font-size: 13px;
        color: #7f8c8d;
    }

    .method-price {
        font-weight: 700;
        color: #27ae60;
    }

    .voucher-section {
        display: flex;
        gap: 10px;
    }

    .voucher-input {
        flex: 1;
    }

    .btn-apply {
        padding: 12px 24px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-apply:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    .voucher-applied {
        background: #d4edda;
        border: 2px solid #28a745;
        padding: 15px;
        border-radius: 10px;
        margin-top: 15px;
        display: none;
    }

    .voucher-applied.show {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Order Summary */
    .order-summary {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        position: sticky;
        top: 100px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: #7f8c8d;
    }

    .summary-value {
        font-weight: 600;
        color: #2c3e50;
    }

    .summary-total {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid #e9ecef;
    }

    .summary-total .summary-label {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
    }

    .summary-total .summary-value {
        font-size: 24px;
        color: #27ae60;
    }

    .btn-place-order {
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

    .btn-place-order:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-place-order:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .error-message {
        color: #e74c3c;
        font-size: 13px;
        margin-top: 5px;
    }

    @media (max-width: 768px) {
        .checkout-container {
            grid-template-columns: 1fr;
        }

        .order-summary {
            position: static;
        }
    }
</style>

<h2 style="font-size: 28px; font-weight: 700; color: #2c3e50; margin-bottom: 25px;">💳 Checkout</h2>

<div class="checkout-container">
    <!-- Left Column -->
    <div>
        <!-- Shipping Address -->
        <div class="checkout-section">
            <div class="section-title">📍 Alamat Pengiriman</div>

            <div class="form-group">
                <label class="form-label">Nama Penerima *</label>
                <input type="text" class="form-input" id="shippingName" value="{{ auth()->user()->name }}" required>
</div>
<div class="form-group">
            <label class="form-label">Nomor Telepon *</label>
            <input type="tel" class="form-input" id="shippingPhone" value="{{ auth()->user()->phone }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Alamat Lengkap *</label>
            <textarea class="form-textarea" id="shippingAddress" required placeholder="Masukkan alamat lengkap dengan RT/RW, Kelurahan, Kecamatan, Kota">{{ auth()->user()->address }}</textarea>
        </div>
    </div>

    <!-- Shipping Method -->
    <div class="checkout-section">
        <div class="section-title">🚚 Metode Pengiriman</div>
        <div class="shipping-methods">
            @foreach($shippingMethods as $method)
            <label class="method-option" onclick="selectShipping('{{ $method['id'] }}', {{ $method['cost'] }})">
                <input type="radio" name="shipping_method" value="{{ $method['id'] }}" class="method-radio" {{ $loop->first ? 'checked' : '' }}>
                <div class="method-info">
                    <div class="method-name">{{ $method['name'] }}</div>
                    <div class="method-detail">Estimasi: {{ $method['estimate'] }}</div>
                </div>
                <div class="method-price">Rp {{ number_format($method['cost'], 0, ',', '.') }}</div>
            </label>
            @endforeach
        </div>
    </div>

    <!-- Voucher -->
    <div class="checkout-section">
        <div class="section-title">🎟️ Kode Voucher</div>
        <div class="voucher-section">
            <input type="text" class="form-input voucher-input" id="voucherCode" placeholder="Masukkan kode voucher">
            <button class="btn-apply" onclick="applyVoucher()">Gunakan</button>
        </div>
        <div class="voucher-applied" id="voucherApplied">
            <div>
                <strong id="appliedVoucherCode"></strong>
                <p style="font-size: 13px; color: #155724; margin-top: 5px;">Diskon: Rp <span id="appliedDiscount">0</span></p>
            </div>
            <button onclick="removeVoucher()" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 20px;">×</button>
        </div>
    </div>

    <!-- Payment Method -->
    <div class="checkout-section">
        <div class="section-title">💳 Metode Pembayaran</div>
        <div class="payment-methods">
            @foreach($paymentMethods as $method)
            <label class="method-option" onclick="selectPayment('{{ $method['id'] }}')">
                <input type="radio" name="payment_method" value="{{ $method['id'] }}" class="method-radio" {{ $loop->first ? 'checked' : '' }}>
                <span style="font-size: 24px;">{{ $method['icon'] }}</span>
                <div class="method-info">
                    <div class="method-name">{{ $method['name'] }}</div>
                </div>
            </label>
            @endforeach
        </div>
    </div>
</div>

<!-- Right Column - Order Summary -->
<div>
    <div class="order-summary">
        <h3 style="margin-bottom: 20px; color: #2c3e50;">📦 Ringkasan Pesanan</h3>

        <div style="margin-bottom: 20px;">
            @foreach($cartItems as $item)
            <div style="display: flex; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f0f0f0;">
                <img src="{{ asset('storage/' . $item->product->main_image) }}" 
                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                <div style="flex: 1;">
                    <div style="font-size: 13px; font-weight: 600; color: #2c3e50;">{{ $item->product->name }}</div>
                    <div style="font-size: 12px; color: #7f8c8d;">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="summary-item">
            <span class="summary-label">Subtotal ({{ $cartItems->sum('quantity') }} item)</span>
            <span class="summary-value" id="summarySubtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>

        <div class="summary-item">
            <span class="summary-label">Ongkir</span>
            <span class="summary-value" id="summaryShipping">Rp {{ number_format($shippingMethods[0]['cost'], 0, ',', '.') }}</span>
        </div>

        <div class="summary-item" id="discountRow" style="display: none;">
            <span class="summary-label">Diskon</span>
            <span class="summary-value" style="color: #e74c3c;" id="summaryDiscount">- Rp 0</span>
        </div>

        <div class="summary-item summary-total">
            <span class="summary-label">TOTAL</span>
            <span class="summary-value" id="summaryTotal">Rp {{ number_format($subtotal + $shippingMethods[0]['cost'], 0, ',', '.') }}</span>
        </div>

        <button class="btn-place-order" onclick="placeOrder()">Buat Pesanan</button>
    </div>
</div>

</div>
<script>
    let subtotal = {{ $subtotal }};
    let shippingCost = {{ $shippingMethods[0]['cost'] }};
    let discount = 0;
    let voucherId = null;
    let selectedShipping = '{{ $shippingMethods[0]['id'] }}';
    let selectedPayment = '{{ $paymentMethods[0]['id'] }}';

    function selectShipping(methodId, cost) {
        selectedShipping = methodId;
        shippingCost = cost;
        
        // Update UI
        document.querySelectorAll('.shipping-methods .method-option').forEach(el => {
            el.classList.remove('selected');
        });
        event.currentTarget.classList.add('selected');
        
        updateTotal();
    }

    function selectPayment(methodId) {
        selectedPayment = methodId;
        
        // Update UI
        document.querySelectorAll('.payment-methods .method-option').forEach(el => {
            el.classList.remove('selected');
        });
        event.currentTarget.classList.add('selected');
    }

    function updateTotal() {
        const total = subtotal + shippingCost - discount;
        
        document.getElementById('summaryShipping').textContent = 'Rp ' + formatNumber(shippingCost);
        document.getElementById('summaryTotal').textContent = 'Rp ' + formatNumber(total);
    }

    function applyVoucher() {
        const code = document.getElementById('voucherCode').value.trim();
        
        if (!code) {
            showToast('Masukkan kode voucher!', 'error');
            return;
        }

        fetch('{{ route('checkout.applyVoucher') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                voucher_code: code,
                subtotal: subtotal
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                discount = data.voucher.discount;
                voucherId = data.voucher.id;
                
                // Show applied voucher
                document.getElementById('appliedVoucherCode').textContent = data.voucher.code;
                document.getElementById('appliedDiscount').textContent = formatNumber(discount);
                document.getElementById('voucherApplied').classList.add('show');
                
                // Show discount row
                document.getElementById('discountRow').style.display = 'flex';
                document.getElementById('summaryDiscount').textContent = '- Rp ' + formatNumber(discount);
                
                // Update total
                updateTotal();
                
                // Disable input
                document.getElementById('voucherCode').disabled = true;
                
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

    function removeVoucher() {
        discount = 0;
        voucherId = null;
        
        document.getElementById('voucherApplied').classList.remove('show');
        document.getElementById('discountRow').style.display = 'none';
        document.getElementById('voucherCode').value = '';
        document.getElementById('voucherCode').disabled = false;
        
        updateTotal();
        
        showToast('Voucher dihapus', 'success');
    }

    function placeOrder() {
        const shippingName = document.getElementById('shippingName').value.trim();
        const shippingPhone = document.getElementById('shippingPhone').value.trim();
        const shippingAddress = document.getElementById('shippingAddress').value.trim();

        if (!shippingName || !shippingPhone || !shippingAddress) {
            showToast('Lengkapi alamat pengiriman!', 'error');
            return;
        }

        const btn = event.target;
        btn.disabled = true;
        btn.textContent = 'Memproses...';

        const total = subtotal + shippingCost - discount;

        fetch('{{ route('checkout.placeOrder') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                shipping_name: shippingName,
                shipping_phone: shippingPhone,
                shipping_address: shippingAddress,
                shipping_method: selectedShipping,
                shipping_cost: shippingCost,
                payment_method: selectedPayment,
                voucher_id: voucherId,
                discount: discount,
                subtotal: subtotal,
                total: total
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                
                // Update cart badge
                updateCartBadge(0, false);
                
                // Redirect to success page
                setTimeout(() => {
                    window.location.href = '{{ route('home') }}';
                }, 1500);
            } else {
                showToast(data.message, 'error');
                btn.disabled = false;
                btn.textContent = 'Buat Pesanan';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
            btn.disabled = false;
            btn.textContent = 'Buat Pesanan';
        });
    }

    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
</script>
@endsection