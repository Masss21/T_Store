@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
        animation: fadeInUp 0.5s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }

    .stat-icon.blue {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-icon.green {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .stat-icon.orange {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-icon.purple {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        font-size: 14px;
        color: #7f8c8d;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
    }

    .stat-change {
        font-size: 12px;
        margin-top: 5px;
    }

    .stat-change.up {
        color: #27ae60;
    }

    .stat-change.down {
        color: #e74c3c;
    }

    .welcome-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .welcome-card h2 {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .welcome-card p {
        font-size: 16px;
        opacity: 0.9;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .action-btn {
        background: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        text-decoration: none;
        color: #2c3e50;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .action-btn .icon {
        font-size: 32px;
        margin-bottom: 10px;
    }

    .action-btn .text {
        font-weight: 600;
        font-size: 14px;
    }
</style>

<!-- Welcome Card -->
<div class="welcome-card">
    <h2>👋 Selamat Datang, {{ auth()->user()->name }}!</h2>
    <p>Kelola toko online Anda dengan mudah melalui dashboard admin T-Store</p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card" style="animation-delay: 0.1s;">
        <div class="stat-icon blue">📦</div>
        <div class="stat-info">
            <div class="stat-label">Total Produk</div>
            <div class="stat-value">{{ \App\Models\Product::count() }}</div>
            <div class="stat-change up">↑ 12% dari bulan lalu</div>
        </div>
    </div>

    <div class="stat-card" style="animation-delay: 0.2s;">
        <div class="stat-icon green">🛒</div>
        <div class="stat-info">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value">{{ \App\Models\Order::count() }}</div>
            <div class="stat-change up">↑ 8% dari bulan lalu</div>
        </div>
    </div>

    <div class="stat-card" style="animation-delay: 0.3s;">
        <div class="stat-icon orange">👥</div>
        <div class="stat-info">
            <div class="stat-label">Total Pelanggan</div>
            <div class="stat-value">{{ \App\Models\User::where('role', 'user')->count() }}</div>
            <div class="stat-change up">↑ 15% dari bulan lalu</div>
        </div>
    </div>

    <div class="stat-card" style="animation-delay: 0.4s;">
        <div class="stat-icon purple">💰</div>
        <div class="stat-info">
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value">Rp {{ number_format(\App\Models\Order::where('payment_status', 'paid')->sum('total'), 0, ',', '.') }}</div>
            <div class="stat-change up">↑ 23% dari bulan lalu</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<h3 style="margin-bottom: 20px; color: #2c3e50; font-size: 20px;">⚡ Aksi Cepat</h3>
<div class="quick-actions">
    <a href="{{ route('admin.products.create') }}" class="action-btn">
        <div class="icon">➕</div>
        <div class="text">Tambah Produk</div>
    </a>

    <a href="{{ route('admin.products.index') }}" class="action-btn">
        <div class="icon">📦</div>
        <div class="text">Kelola Produk</div>
    </a>

    <a href="{{ route('admin.categories.index') }}" class="action-btn">
        <div class="icon">🏷️</div>
        <div class="text">Kelola Kategori</div>
    </a>

    <a href="{{ route('admin.vouchers.index') }}" class="action-btn">
        <div class="icon">🎟️</div>
        <div class="text">Buat Voucher</div>
    </a>

    <a href="{{ route('admin.orders.index') }}" class="action-btn">
        <div class="icon">📋</div>
        <div class="text">Lihat Pesanan</div>
    </a>

    <a href="{{ route('admin.customers.index') }}" class="action-btn">
        <div class="icon">👥</div>
        <div class="text">Data Pelanggan</div>
    </a>
</div>

@endsection