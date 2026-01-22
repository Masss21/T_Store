@extends('admin.layouts.app')

@section('title', 'Kelola Produk')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    .table-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
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
        font-size: 14px;
        text-transform: uppercase;
    }

    td {
        color: #495057;
    }

    tbody tr {
        transition: background 0.2s;
    }

    tbody tr:hover {
        background: #f8f9fa;
    }

    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-sm {
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-edit {
        background: #3498db;
        color: white;
    }

    .btn-edit:hover {
        background: #2980b9;
    }

    .btn-delete {
        background: #e74c3c;
        color: white;
    }

    .btn-delete:hover {
        background: #c0392b;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
    }

    .pagination a, .pagination span {
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        color: #495057;
        text-decoration: none;
        transition: all 0.3s;
    }

    .pagination a:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .pagination .active span {
        background: #667eea;
        color: white;
        border-color: #667eea;
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
</style>

<div class="page-header">
    <h2 style="color: #2c3e50; font-size: 24px;">📦 Daftar Produk</h2>
    <a href="{{ route('admin.products.create') }}" class="btn-primary">
        <span>➕</span>
        <span>Tambah Produk</span>
    </a>
</div>

<div class="table-container">
    @if($products->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>
                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="product-image">
                    </td>
                    <td>
                        <strong>{{ $product->name }}</strong>
                        @if($product->is_featured)
                            <span class="badge badge-warning" style="margin-left: 8px;">⭐ Featured</span>
                        @endif
                    </td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        <strong style="color: {{ $product->stock > 10 ? '#27ae60' : ($product->stock > 0 ? '#f39c12' : '#e74c3c') }}">
                            {{ $product->stock }}
                        </strong>
                    </td>
                    <td>
                        @if($product->status == 'available')
                            <span class="badge badge-success">Tersedia</span>
                        @elseif($product->status == 'out_of_stock')
                            <span class="badge badge-danger">Habis</span>
                        @else
                            <span class="badge badge-warning">{{ ucfirst($product->status) }}</span>
                        @endif
                    </td>
                    <td>
    <div class="action-buttons">
        <a href="{{ route('admin.products.edit', $product) }}" class="btn-sm btn-edit">✏️ Edit</a>
       <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline;" 
      onsubmit="confirmDelete(event, 'produk {{ $product->name }}')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-sm btn-delete">🗑️ Hapus</button>
</form>
    </div>
</td>

<td>
    @php
        $imagePath = asset('storage/' . $product->main_image);
        $realPath = storage_path('app/public/' . $product->main_image);
    @endphp
    
    <img src="{{ $imagePath }}" 
         alt="{{ $product->name }}" 
         class="product-image"
         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Crect fill=%22%23ddd%22 width=%22100%22 height=%22100%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23999%22 font-size=%2216%22%3ENo Image%3C/svg%3E'">
    
    <!-- Debug Info (hapus setelah fix) -->
    <div style="font-size: 10px; color: #999;">
        Path: {{ $product->main_image }}<br>
        Exists: {{ file_exists($realPath) ? '✓' : '✗' }}
    </div>
</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $products->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="icon">📦</div>
            <h3>Belum Ada Produk</h3>
            <p>Mulai tambahkan produk pertama Anda</p>
            <a href="{{ route('admin.products.create') }}" class="btn-primary" style="margin-top: 20px;">
                <span>➕</span>
                <span>Tambah Produk</span>
            </a>
        </div>
    @endif
</div>
@endsection
<script>
function deleteProduct(id, name) {
    if (confirm('Yakin ingin menghapus produk "' + name + '"?\n\nProduk akan dihapus permanen dari database.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>