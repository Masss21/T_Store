@extends('admin.layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #3498db;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .back-link:hover {
        color: #2980b9;
        transform: translateX(-5px);
    }

    .order-header {
        background: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .order-number {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .order-date {
        color: #7f8c8d;
        font-size: 14px;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e9ecef;
    }

    .order-items {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .order-item {
        display: flex;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .item-price {
        color: #7f8c8d;
        font-size: 14px;
    }

    .item-total {
        text-align: right;
        font-weight: 700;
        color: #27ae60;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #7f8c8d;
        font-size: 14px;
    }

    .info-value {
        color: #2c3e50;
        font-weight: 600;
        text-align: right;
    }

    .total-row {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid #e9ecef;
    }

    .total-row .info-label {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
    }

    .total-row .info-value {
        font-size: 24px;
        color: #27ae60;
    }

    .status-update-form {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .status-update-form select {
        flex: 1;
        padding: 10px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
    }

    .btn-update {
        padding: 10px 20px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-update:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    .badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }

    .badge-processing {
        background: #cfe2ff;
        color: #084298;
    }

    .badge-shipped {
        background: #cff4fc;
        color: #055160;
    }

    .badge-delivered {
        background: #d1e7dd;
        color: #0f5132;
    }

    .badge-cancelled {
        background: #f8d7da;
        color: #842029;
    }

    .badge-paid {
        background: #d1e7dd;
        color: #0f5132;
    }

    .badge-unpaid {
        background: #f8d7da;
        color: #842029;
    }

    @media (max-width: 768px) {
        .content-grid {
            grid-template-columns: 1fr;
        }

        .order-item {
            flex-direction: column;
        }

        .item-total {
            text-align: left;
        }
    }
</style>

<a href="{{ route('admin.orders.index') }}" class="back-link">
    ← Kembali ke Daftar Transaksi
</a>

<!-- Order Header -->
<div class="order-header">
    <div class="order-number">Order #{{ $order->order_number }}</div>
    <div class="order-date">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }} WIB</div>
</div>

<!-- Content Grid -->
<div class="content-grid">
    <!-- Left Column -->
    <div style="display: flex; flex-direction: column; gap: 25px;">
        <!-- Order Items -->
        <div class="card">
            <div class="card-title">📦 Produk yang Dipesan</div>
            <div class="order-items">
                @foreach($order->orderItems as $item)
                <div class="order-item">
                    @if($item->product)
                        <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product_name }}" class="item-image">
                    @else
                        <div class="item-image" style="background: #e9ecef; display: flex; align-items: center; justify-content: center; font-size: 32px;">
                            📦
                        </div>
                    @endif
                    <div class="item-details">
                        <div class="item-name">{{ $item->product_name }}</div>
                        <div class="item-price">
                            Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}
                        </div>
                    </div>
                    <div class="item-total">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Price Summary -->
            <div style="margin-top: 20px;">
                <div class="info-row">
                    <span class="info-label">Subtotal</span>
                    <span class="info-value">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($order->discount > 0)
                <div class="info-row">
                    <span class="info-label">Diskon @if($order->voucher)({{ $order->voucher->code }})@endif</span>
                    <span class="info-value" style="color: #e74c3c;">- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Ongkir ({{ $order->shipping_method }})</span>
                    <span class="info-value">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="info-row total-row">
                    <span class="info-label">TOTAL</span>
                    <span class="info-value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Shipping Info -->
        <div class="card">
            <div class="card-title">🚚 Informasi Pengiriman</div>
            <div class="info-row">
                <span class="info-label">Nama Penerima</span>
                <span class="info-value">{{ $order->shipping_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">No. Telepon</span>
                <span class="info-value">{{ $order->shipping_phone }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Alamat</span>
                <span class="info-value" style="text-align: right; max-width: 60%;">{{ $order->shipping_address }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Metode Pengiriman</span>
                <span class="info-value">{{ $order->shipping_method }}</span>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div style="display: flex; flex-direction: column; gap: 25px;">
        <!-- Customer Info -->
        <div class="card">
            <div class="card-title">👤 Informasi Customer</div>
            <div class="info-row">
                <span class="info-label">Nama</span>
                <span class="info-value">{{ $order->user->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $order->user->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">No. Telp</span>
                <span class="info-value">{{ $order->user->phone }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="card">
            <div class="card-title">💳 Informasi Pembayaran</div>
            <div class="info-row">
                <span class="info-label">Metode</span>
                <span class="info-value">{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">
                    @if($order->payment_status == 'paid')
                        <span class="badge badge-paid">✓ Lunas</span>
                    @elseif($order->payment_status == 'failed')
                        <span class="badge badge-unpaid">✗ Gagal</span>
                    @else
                        <span class="badge badge-unpaid">⏳ Belum Bayar</span>
                    @endif
                </span>
            </div>
            @if($order->paid_at)
            <div class="info-row">
                <span class="info-label8:12 PM">Dibayar pada</span> <span class="info-value">{{ $order->paid_at->format('d M Y, H:i') }}</span> 
</div> 
@endif

<!-- Update Payment Status -->
        <form action="{{ route('admin.orders.updatePaymentStatus', $order) }}" method="POST" class="status-update-form">
            @csrf
            @method('PUT')
            <select name="payment_status" required>
                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit" class="btn-update">Update</button>
        </form>
    </div>

    <!-- Order Status -->
    <div class="card">
        <div class="card-title">📊 Status Order</div>
        <div class="info-row">
            <span class="info-label">Status Saat Ini</span>
            <span class="info-value">
                @if($order->status == 'pending')
                    <span class="badge badge-pending">⏳ Pending</span>
                @elseif($order->status == 'processing')
                    <span class="badge badge-processing">🔄 Processing</span>
                @elseif($order->status == 'shipped')
                    <span class="badge badge-shipped">🚚 Shipped</span>
                @elseif($order->status == 'delivered')
                    <span class="badge badge-delivered">✓ Delivered</span>
                @else
                    <span class="badge badge-cancelled">✗ Cancelled</span>
                @endif
            </span>
        </div>

        <!-- Update Order Status -->
        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="status-update-form">
            @csrf
            @method('PUT')
            <select name="status" required>
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn-update">Update</button>
        </form>
    </div>

    @if($order->notes)
    <!-- Notes -->
    <div class="card">
        <div class="card-title">📝 Catatan</div>
        <p style="color: #495057; line-height: 1.6;">{{ $order->notes }}</p>
    </div>
    @endif
</div>
</div>
@endsection
