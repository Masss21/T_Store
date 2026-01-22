@extends('admin.layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<style>
    .form-container {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        max-width: 900px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #2c3e50;
        font-weight: 600;
        font-size: 14px;
    }

    input[type="text"],
    input[type="number"],
    select,
    textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s;
    }

    input:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    textarea {
        min-height: 120px;
        resize: vertical;
    }

    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }

    .file-input-wrapper input[type=file] {
        position: absolute;
        left: -9999px;
    }

    .file-input-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 40px;
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s;
        background: #f8f9fa;
    }

    .file-input-label:hover {
        border-color: #667eea;
        background: #f0f3ff;
    }

    .image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 15px;
    }

    .preview-item {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .checkbox-group input[type="checkbox"] {
        width: auto;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 30px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .error-message {
        color: #e74c3c;
        font-size: 12px;
        margin-top: 5px;
    }

    .helper-text {
        font-size: 12px;
        color: #7f8c8d;
        margin-top: 5px;
    }
</style>

<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.products.index') }}" style="color: #667eea; text-decoration: none; font-weight: 600;">
        ← Kembali ke Daftar Produk
    </a>
</div>

<div class="form-container">
    <h2 style="margin-bottom: 25px; color: #2c3e50;">➕ Tambah Produk Baru</h2>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="category_id">Kategori *</label>
            <select name="category_id" id="category_id" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="name">Nama Produk *</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: iPhone 15 Pro Max" required>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Deskripsi *</label>
            <textarea name="description" id="description" required placeholder="Jelaskan detail produk...">{{ old('description') }}</textarea>
            @error('description')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="specifications">Spesifikasi</label>
            <textarea name="specifications" id="specifications" placeholder="Format: Nama:Nilai (satu per baris)
Contoh:
Processor:Apple A17 Pro
RAM:8GB
Storage:256GB">{{ old('specifications') }}</textarea>
            <div class="helper-text">Format: Nama:Nilai (satu per baris)</div>
            @error('specifications')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="price">Harga (Rp) *</label>
                <input type="number" name="price" id="price" value="{{ old('price') }}" min="0" step="1000" required>
                @error('price')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="stock">Stok *</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0" required>
                @error('stock')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
    <label>Gambar Utama *</label>
    <div class="file-input-wrapper">
        <input type="file" name="main_image" id="main_image" accept="image/*" required onchange="previewMainImage(event)">
        <label for="main_image" class="file-input-label">
            <span style="font-size: 32px;">📷</span>
            <span>Klik untuk upload gambar utama</span>
        </label>
    </div>
    <div class="helper-text">Rekomendasi: Minimal 500x500 px, Max 2MB</div>
    <div class="image-preview" id="mainImagePreview"></div>
    @error('main_image')
        <div class="error-message">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label>Galeri Gambar (Opsional)</label>
    <div class="file-input-wrapper">
        <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple onchange="previewGalleryImages(event)">
        <label for="gallery_images" class="file-input-label">
            <span style="font-size: 32px;">🖼️</span>
            <span>Klik untuk upload multiple gambar</span>
        </label>
    </div>
    <div class="helper-text">Rekomendasi: Minimal 500x500 px per gambar, Max 2MB</div>
    <div class="image-preview" id="galleryPreview"></div>
    @error('gallery_images.*')
        <div class="error-message">{{ $message }}</div>
    @enderror
</div>

        <div class="form-group">
            <div class="checkbox-group">
                <input type="checkbox" name="is_featured" id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                <label for="is_featured" style="margin: 0;">⭐ Jadikan Produk Featured</label>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Simpan Produk</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">❌ Batal</a>
        </div>
    </form>
</div>

<script>
function previewMainImage(event) {
    const preview = document.getElementById('mainImagePreview');
    preview.innerHTML = '';
    
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            preview.appendChild(div);
        }
        reader.readAsDataURL(file);
    }
}

function previewGalleryImages(event) {
    const preview = document.getElementById('galleryPreview');
    preview.innerHTML = '';
    
    const files = event.target.files;
    for (let i = 0; i < files.length; i++) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            preview.appendChild(div);
        }
        reader.readAsDataURL(files[i]);
    }
}
</script>
@endsection