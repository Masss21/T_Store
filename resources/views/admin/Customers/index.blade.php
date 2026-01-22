@extends('admin.layouts.app')

@section('title', 'Kelola Pelanggan')

@section('content')
<style>
    .header-section {
        background: white;
        padding: 20px 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .header-section:hover {
        border-color: #3498db;
        box-shadow: 0 6px 20px rgba(52, 152, 219, 0.15);
        transform: translateY(-2px);
    }

    .header-title {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .btn-export {
        padding: 12px 24px;
        background: #27ae60;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
    }

    .btn-export:hover {
        background: #229954;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        text-align: center;
        cursor: pointer;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        border-color: #3498db;
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 12px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 13px;
        color: #7f8c8d;
        font-weight: 500;
    }

    .filters-section {
        background: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .filters-section:hover {
        border-color: #3498db;
        box-shadow: 0 6px 20px rgba(52, 152, 219, 0.12);
    }

    .filters-row {
    display: flex; /* Ubah dari grid ke flex */
    flex-wrap: wrap; /* Agar responsif jika layar sempit */
    gap: 15px; /* Jarak antar elemen */
    align-items: flex-end; /* Membuat tombol sejajar dengan input, bukan label */
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
        width: auto;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .btn-filter {
        padding: 10px 24px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        height: fit-content;
    }

    .btn-filter:hover {
        background: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    .btn-reset {
        padding: 10px 24px;
        background: #95a5a6;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        height: fit-content;
    }

    .btn-reset:hover {
        background: #7f8c8d;
        transform: translateY(-2px);
    }

    .customers-table {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow-x: auto;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .customers-table:hover {
        border-color: #3498db;
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
        transition: all 0.2s ease;
    }

    tbody tr:hover {
        background: #f8f9fa;
        transform: scale(1.01);
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-verified {
        background: #d1e7dd;
        color: #0f5132;
    }

    .badge-unverified {
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
        margin-right: 5px;
    }

    .btn-detail:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    .btn-delete {
        padding: 8px 16px;
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-delete:hover {
        background: #c0392b;
        transform: translateY(-2px);
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

    @media (max-width: 968px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .filters-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .header-section {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@if(session('success'))
    <div style="background: #d1e7dd; color: #0f5132; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #0f5132;">
        ✅ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #f8d7da; color: #842029; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #842029;">
        ❌ {{ session('error') }}
    </div>
@endif

<!-- Header Section -->
<div class="header-section">
    <h2 class="header-title">👥 Kelola Pelanggan</h2>
    
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-value">{{ \App\Models\User::where('role', 'user')->count() }}</div>
        <div class="stat-label">Total Pelanggan</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-value">{{ \App\Models\User::where('role', 'user')->whereNotNull('email_verified_at')->count() }}</div>
        <div class="stat-label">Terverifikasi</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">🛒</div>
        <div class="stat-value">{{ \App\Models\User::where('role', 'user')->withCount('orders')->get()->sum('orders_count') }}</div>
        <div class="stat-label">Total Pesanan</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">📅</div>
        <div class="stat-value">{{ \App\Models\User::where('role', 'user')->whereDate('created_at', today())->count() }}</div>
        <div class="stat-label">Registrasi Hari Ini</div>
    </div>
</div>

<!-- Filters Section -->
<div class="filters-section">
    <form method="GET" action="{{ route('admin.customers.index') }}">
        <div class="filters-row">
            <div class="filter-group">
                <label>🔍 Cari Pelanggan</label>
                <input type="text" name="search" placeholder="Nama, email, atau telepon..." value="{{ request('search') }}">
            </div>

            <div class="filter-group">
                <label>✅ Status Verifikasi</label>
                <select name="verified">
                    <option value="">Semua</option>
                    <option value="yes" {{ request('verified') == 'yes' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="no" {{ request('verified') == 'no' ? 'selected' : '' }}>Belum Verifikasi</option>
                </select>
            </div>

            <div class="filter-group">
                <label>📊 Urutkan</label>
                <select name="sort_by">
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama</option>
                    <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                </select>
                
            </div>

            <button type="submit" class="btn-filter">Filter</button>
            <a href="{{ route('admin.customers.index') }}" class="btn-reset">Reset</a>
            <a href="{{ route('admin.customers.export') }}" class="btn-export">📥 Export CSV</a>
        </div>
    </form>
</div>

<!-- Customers Table -->
<div class="customers-table">
    @if($customers->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th style="text-align: center;">Pesanan</th>
                    <th style="text-align: center;">Keranjang</th>
                    <th style="text-align: center;">Wishlist</th>
                    <th>Status</th>
                    <th>Terdaftar</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td><strong style="color: #3498db;">#{{ $customer->id }}</strong></td>
                    <td><strong>{{ $customer->name }}</strong></td>
                    <td style="color: #7f8c8d;">{{ $customer->email }}</td>
                    <td style="color: #7f8c8d;">{{ $customer->phone ?? '-' }}</td>
                    <td style="text-align: center;">
                        <span style="padding: 5px 12px; background: #dbeafe; color: #1e40af; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            {{ $customer->orders_count }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <span style="padding: 5px 12px; background: #fef3c7; color: #92400e; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            {{ $customer->carts_count }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <span style="padding: 5px 12px; background: #fce7f3; color: #9f1239; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            {{ $customer->wishlists_count }}
                        </span>
                    </td>
                    <td>
                        @if($customer->email_verified_at)
                            <span class="badge badge-verified">✓ Verified</span>
                        @else
                            <span class="badge badge-unverified">✗ Unverified</span>
                        @endif
                    </td>
                    <td style="color: #7f8c8d;">{{ $customer->created_at->format('d M Y') }}</td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="btn-detail">👁️ Detail</a>
                        <form action="{{ route('admin.customers.destroy', $customer) }}" 
                              method="POST" 
                              style="display: inline;" 
                              onsubmit="return confirm('Yakin hapus pelanggan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $customers->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="icon">👥</div>
            <h3>Belum Ada Pelanggan</h3>
            <p>Data pelanggan akan muncul di sini</p>
        </div>
    @endif
</div>
@endsection