@extends('admin.layouts.app')

@section('title', '🏷️ Kelola Kategori')

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

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
    }

    .category-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
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

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .category-image {
        width: 100%;
        height: 200px; /* Ubah dari 150px menjadi 200px untuk lebih jelas */
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 15px;
        background: #f8f9fa;
    }

    .category-name {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .category-desc {
        font-size: 14px;
        color: #7f8c8d;
        margin-bottom: 15px;
        line-height: 1.5;
    }

    .category-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-top: 1px solid #e9ecef;
        margin-bottom: 15px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
        color: #495057;
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

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-sm {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        flex: 1;
        text-align: center;
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
</style>

<div class="page-header">
    <a href="{{ route('admin.categories.create') }}" class="btn-primary">
        <span>➕</span>
        <span>Tambah Kategori</span>
    </a>
</div>

<div class="categories-grid">
    @forelse($categories as $category)
    <div class="category-card">
        @if($category->image)
    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="category-image">
@else
    <div class="category-image" style="display: flex; align-items: center; justify-content: center; font-size: 64px;">
        🏷️
    </div>
@endif

        <div class="category-name">{{ $category->name }}</div>
        <div class="category-desc">{{ $category->description ?? 'Tidak ada deskripsi' }}</div>

        <div class="category-stats">
            <div class="stat-item">
                <span>📦</span>
                <span><strong>{{ $category->products_count }}</strong> Produk</span>
            </div>
            @if($category->is_active)
                <span class="badge badge-success">✓ Aktif</span>
            @else
                <span class="badge badge-danger">✗ Nonaktif</span>
            @endif
        </div>

        <div class="action-buttons">
    <a href="{{ route('admin.categories.edit', $category) }}" class="btn-sm btn-edit">✏️ Edit</a>
    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="flex: 1;"
      onsubmit="confirmDelete(event, 'kategori {{ $category->name }}')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-sm btn-delete" style="width: 100%;">🗑️ Hapus</button>
</form>
</div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #7f8c8d;">
        <div style="font-size: 64px; margin-bottom: 20px;">🏷️</div>
        <h3>Belum Ada Kategori</h3>
        <p>Mulai tambahkan kategori pertama Anda</p>
    </div>
    @endforelse
</div>

@if($categories->hasPages())
<div style="margin-top: 30px; display: flex; justify-content: center;">
    {{ $categories->links() }}
</div>
@endif
@endsection
<script>
function deleteCategory(id, name, productCount) {
    if (productCount > 0) {
        alert('⚠️ Kategori "' + name + '" tidak dapat dihapus!\n\nMasih ada ' + productCount + ' produk yang menggunakan kategori ini.');
        return false;
    }
    
    if (confirm('Yakin ingin menghapus kategori "' + name + '"?\n\nKategori akan dihapus permanen.')) {
        document.getElementById('delete-category-form-' + id).submit();
    }
}
</script>