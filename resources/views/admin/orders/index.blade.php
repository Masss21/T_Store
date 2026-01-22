@extends('admin.layouts.app')

@section('title', 'Kelola Transaksi')

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }

    .stat-label {
        font-size: 13px;
        color: #7f8c8d;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
    }

    .filters-section {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .filters-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr auto;
        gap: 15px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-group label {
        font-size: 13px;
        font-weight: 600;
        color: #2c3e50;
    }

    .filter-group input,
    .filter-group select {
        padding: 10px 12px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .btn-filter {
        padding: 10px 20px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-filter:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    .btn-reset {
        padding: 10px 20px;
        background: #95a5a6;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-reset:hover {
        background: #7f8c8d;
    }

    .orders-table {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #f8f9fa;
    }

    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #e9ecef;
    }

    th {
        font-weight: 600;
        color: #2c3e50;
        font-size: 13px;
        text-transform: uppercase;
    }

    td {
        color: #495057;
        font-size: 14px;
    }

    tbody tr {
        transition: background 0.2s;
    }

    tbody tr:hover {
        background: #f8f9fa;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
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

    .badge-failed {
        background: #f8d7da;
        color: #842029;
    }

    .btn-detail {
        padding: 8px 16px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-detail:hover {
        background: #2980b9;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #7f8c8d;
    }

    .empty-state .icon {
        font-size: 64px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .filters-row {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<h2 style="color: #2c3e50; font-size: 24px; margin-bottom: 25px;">🛒 Kelola Transaksi</h2>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Order</div>
        <div class="stat-value">{{ $stats['total_orders'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pending</div>
        <div class="stat-value" style="color: #f39c12;">{{ $stats['pending'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Processing</div>
        <div class="stat-value" style="color: #3498db;">{{ $stats['processing'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Shipped</div>
        <div class="stat-value" style="color: #17a2b8;">{{ $stats['shipped'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Delivered</div>
        <div class="stat-value" style="color: #27ae60;">{{ $stats['delivered'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Cancelled</div>
        <div class="stat-value" style="color: #e74c3c;">{{ $stats['cancelled'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value" style="color: #27ae60; font-size: 20px;">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
    </div>
</div>

<!-- Filters -->
<div class="filters-section">
    <form method="GET" action="{{ route('admin.orders.index') }}">
        <div class="filters-row">
            <div class="filter-group">
                <label>Cari Order</label>
                <input type="text" name="search" placeholder="No. Order / Nama Customer" value="{{ request('search') }}">
            </div>

            <div class="filter-group">
                <label>Status Order</label>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Status Pembayaran</label>
                <select name="payment_status">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <button type="submit" class="btn-filter">🔍 Filter</button>
            <a href="{{ route('admin.orders.index') }}" class="btn-reset">Reset</a>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="orders-table">
    @if($orders->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No. Order</th>
                    <th>Customer</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status Order</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>
                        <strong>{{ $order->order_number }}</strong>
                        <br>
                        <small style="color: #7f8c8d;">{{ $order->orderItems->count() }} item</small>
                    </td>
                    <td>
                        <strong>{{ $order->user->name }}</strong>
                        <br>
                        <small style="color: #7f8c8d;">{{ $order->user->email }}</small>
                    </td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <strong style="color: #27ae60;">Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                    </td>
                    <td>
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
                    </td>
                    <td>
                        @if($order->payment_status == 'paid')
                            <span class="badge badge-paid">✓ Paid</span>
                        @elseif($order->payment_status == 'failed')
                            <span class="badge badge-failed">✗ Failed</span>
                        @else
                            <span class="badge badge-unpaid">⏳ Pending</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn-detail">👁️ Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $orders->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="icon">🛒</div>
            <h3>Belum Ada Transaksi</h3>
            <p>Transaksi dari pelanggan akan muncul di sini</p>
        </div>
    @endif
</div>
@endsection