@extends('admin.layouts.app')

@section('title', 'Kelola Pelanggan')

@section('content')
<style>
    /* ... semua CSS tetap sama ... */
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
        color: white;
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
        padding: 20px 25px;
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
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-item {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        min-width: 200px;
    }

    .filter-item label {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .filter-item input,
    .filter-item select {
        flex: 1;
        padding: 10px 14px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
    }

    .filter-item input:focus,
    .filter-item select:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .filter-divider {
        width: 1px;
        height: 30px;
        background: #e9ecef;
        margin: 0 5px;
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
        color: white;
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

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 968px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .filters-row {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-item {
            min-width: 100%;
        }
        .filter-divider {
            display: none;
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
    <form method="GET" action="{{ route('admin.customers.index') }}" id="filterForm">
        <div class="filters-row">
            <div class="filter-item">
                <label>🔍 Search</label>
                <input type="text" 
                       name="search" 
                       id="searchInput"
                       placeholder="Nama, email, atau telepon..." 
                       value="{{ request('search') }}"
                       autocomplete="off">
            </div>

            <div class="filter-divider"></div>

            <div class="filter-item">
                <label>✅ Status</label>
                <select name="verified" id="verifiedSelect">
                    <option value="">Semua</option>
                    <option value="yes" {{ request('verified') == 'yes' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="no" {{ request('verified') == 'no' ? 'selected' : '' }}>Belum Verifikasi</option>
                </select>
            </div>

            <div class="filter-divider"></div>

            <div class="filter-item">
                <label>📊 Sort</label>
                <select name="sort_by" id="sortSelect">
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama</option>
                    <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                </select>
            </div>

            <div class="filter-divider"></div>

            <a href="{{ route('admin.customers.export') }}" class="btn-export">📥 Export</a>
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
                        <button onclick="showCustomerDetail({{ $customer->id }})" class="btn-detail">👁️ Detail</button>
                        
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

<!-- Modal Detail Pelanggan -->
<div id="customerDetailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; overflow-y: auto;">
    <div style="max-width: 1200px; margin: 50px auto; background: white; border-radius: 15px; padding: 0; position: relative; animation: slideDown 0.3s ease;">
        <div style="padding: 25px; border-bottom: 2px solid #e9ecef; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0; font-size: 24px; color: #2c3e50;">👤 Detail Pelanggan</h2>
            <button onclick="closeCustomerDetail()" style="background: none; border: none; font-size: 28px; cursor: pointer; color: #7f8c8d; line-height: 1;">&times;</button>
        </div>
        
        <div id="customerDetailContent" style="padding: 30px;">
            <div style="text-align: center; padding: 40px;">
                <div style="font-size: 48px; margin-bottom: 15px;">⏳</div>
                <div style="color: #7f8c8d;">Loading...</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto submit filter
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const verifiedSelect = document.getElementById('verifiedSelect');
        const sortSelect = document.getElementById('sortSelect');
        const form = document.getElementById('filterForm');
        
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                form.submit();
            }, 500);
        });
        
        verifiedSelect.addEventListener('change', function() {
            form.submit();
        });
        
        sortSelect.addEventListener('change', function() {
            form.submit();
        });
    });

    // Show Customer Detail Modal
    function showCustomerDetail(customerId) {
        const modal = document.getElementById('customerDetailModal');
        const content = document.getElementById('customerDetailContent');
        
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        content.innerHTML = `
            <div style="text-align: center; padding: 40px;">
                <div style="font-size: 48px; margin-bottom: 15px;">⏳</div>
                <div style="color: #7f8c8d;">Loading...</div>
            </div>
        `;
        
        fetch(`/admin/customers/${customerId}/detail`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            content.innerHTML = renderCustomerDetail(data);
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #e74c3c;">
                    <div style="font-size: 48px; margin-bottom: 15px;">❌</div>
                    <div>Gagal memuat data pelanggan</div>
                    <button onclick="closeCustomerDetail()" style="margin-top: 20px; padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer;">Tutup</button>
                </div>
            `;
        });
    }

    // Close Modal
    function closeCustomerDetail() {
        document.getElementById('customerDetailModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Close on outside click
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('customerDetailModal');
        if (e.target === modal) closeCustomerDetail();
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeCustomerDetail();
    });

    // Render Customer Detail
    function renderCustomerDetail(data) {
        const customer = data.customer;
        const stats = data.stats;
        
        return `
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
                <div>
                    <div style="background: white; padding: 25px; border-radius: 12px; border: 2px solid #e9ecef;">
                        <div style="text-align: center; margin-bottom: 20px;">
                            <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 48px; color: white;">
                                ${customer.name.charAt(0).toUpperCase()}
                            </div>
                            <h3 style="font-size: 22px; margin-bottom: 5px; color: #2c3e50;">${customer.name}</h3>
                            <p style="color: #7f8c8d; font-size: 14px;">ID: #${customer.id}</p>
                        </div>
                        
                        <div style="border-top: 2px solid #e9ecef; padding-top: 20px;">
                            <div style="margin-bottom: 15px;">
                                <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">📧 Email</div>
                                <div style="font-weight: 600; color: #2c3e50;">${customer.email}</div>
                            </div>
                            
                            <div style="margin-bottom: 15px;">
                                <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">📱 Telepon</div>
                                <div style="font-weight: 600; color: #2c3e50;">${customer.phone || '-'}</div>
                            </div>
                            
                            <div style="margin-bottom: 15px;">
                                <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">📅 Terdaftar</div>
                                <div style="font-weight: 600; color: #2c3e50;">${formatDate(customer.created_at)}</div>
                            </div>
                            
                            <div>
                                <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">✅ Status</div>
                                <span style="padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; ${customer.email_verified_at ? 'background: #d1e7dd; color: #0f5132;' : 'background: #f8d7da; color: #842029;'}">
                                    ${customer.email_verified_at ? '✓ Terverifikasi' : '✗ Belum Verifikasi'}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 25px;">
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 12px; color: white; text-align: center;">
                            <div style="font-size: 32px; margin-bottom: 8px;">🛍️</div>
                            <div style="font-size: 28px; font-weight: 700;">${stats.total_orders}</div>
                            <div style="font-size: 12px; opacity: 0.9;">Total Pesanan</div>
                        </div>
                        
                        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 12px; color: white; text-align: center;">
                            <div style="font-size: 32px; margin-bottom: 8px;">💰</div>
                            <div style="font-size: 16px; font-weight: 700;">Rp ${formatNumber(stats.total_spent)}</div>
                            <div style="font-size: 12px; opacity: 0.9;">Total Belanja</div>
                        </div>
                        
                        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 20px; border-radius: 12px; color: white; text-align: center;">
                            <div style="font-size: 32px; margin-bottom: 8px;">✅</div>
                            <div style="font-size: 28px; font-weight: 700;">${stats.completed_orders}</div>
                            <div style="font-size: 12px; opacity: 0.9;">Selesai</div>
                        </div>
                    </div>
                    
                    <div style="background: white; padding: 25px; border-radius: 12px; border: 2px solid #e9ecef;">
                        <h4 style="margin: 0 0 20px 0; font-size: 16px; color: #2c3e50;">🛍️ Riwayat Pesanan Terakhir</h4>
                        ${stats.recent_orders && stats.recent_orders.length > 0 ? `
                            <div style="overflow-x: auto;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background: #f8f9fa; text-align: left;">
                                            <th style="padding: 10px; font-size: 12px;">Order Number</th>
                                            <th style="padding: 10px; font-size: 12px;">Total</th>
                                            <th style="padding: 10px; font-size: 12px;">Status</th>
                                            <th style="padding: 10px; font-size: 12px;">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${stats.recent_orders.map(order => `
                                            <tr style="border-bottom: 1px solid #e9ecef;">
                                                <td style="padding: 10px; font-weight: 600; font-size: 13px;">${order.order_number}</td>
                                                <td style="padding: 10px; font-weight: 700; color: #27ae60; font-size: 13px;">Rp ${formatNumber(order.total)}</td>
                                                <td style="padding: 10px;">
                                                    <span style="padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; ${getOrderStatusStyle(order.status)}">
                                                        ${capitalizeFirst(order.status)}
                                                    </span>
                                                </td>
                                                <td style="padding: 10px; color: #7f8c8d; font-size: 12px;">${formatDate(order.created_at)}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        ` : '<p style="text-align: center; color: #7f8c8d; padding: 30px;">Belum ada pesanan</p>'}
                    </div>
                </div>
            </div>
        `;
    }

    // Helper functions
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    }

    function capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function getOrderStatusStyle(status) {
        const styles = {
            'completed': 'background: #d1e7dd; color: #0f5132;',
            'pending': 'background: #fff3cd; color: #856404;',
            'cancelled': 'background: #f8d7da; color: #842029;',
            'processing': 'background: #cfe2ff; color: #084298;',
            'shipped': 'background: #cff4fc; color: #055160;',
            'delivered': 'background: #d1e7dd; color: #0f5132;'
        };
        return styles[status]|| 'background: #e9ecef; color: #495057;';
}
</script>
@endpush
@endsection
