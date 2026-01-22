@extends('admin.layouts.app')

@section('title', 'Edit Produk')

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

    .current-image {
        margin-top: 10px;
        border-radius: 10px;
        overflow: hidden;
        display: inline-block;
    }

    .current-image img {
        max-width: 200px;
        height: auto;
        border-radius: 10px;
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
        padding: 20px;
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
    <a href="{{route('admin.products.index') }}" style="color: #667eea; text-decoration: none; font-weight: 600;">
← Kembali ke Daftar Produk
</a>
</div>
<div class="form-container">
    <h2 style="margin-bottom: 25px; color: #2c3e50;">✏️ Edit Produk</h2>
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="category_id">Kategori *</label>
        <select name="category_id" id="category_id" required>
            <option value="">Pilih Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
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
        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required>
        @error('name')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="description">Deskripsi *</label>
        <textarea name="description" id="description" required>{{ old('description', $product->description) }}</textarea>
        @error('description')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="specifications">Spesifikasi</label>
        <textarea name="specifications" id="specifications" placeholder="Format: Nama:Nilai (satu per baris)">{{ old('specifications', $product->specifications ? implode("\n", array_map(fn($k, $v) => "$k:$v", array_keys($product->specifications), $product->specifications)) : '') }}</textarea>
        <div class="helper-text">Format: Nama:Nilai (satu per baris)</div>
        @error('specifications')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="form-group">
            <label for="price">Harga (Rp) *</label>
            <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" min="0" step="1000" required>
            @error('price')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="stock">Stok *</label>
            <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
            @error('stock')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label>Gambar Utama</label>
        <div class="current-image">
            <p style="margin-bottom: 10px; color: #7f8c8d; font-size: 14px;">Gambar Saat Ini:</p>
            <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}">
        </div>
        <div class="file-input-wrapper" style="margin-top: 15px;">
            <input type="file" name="main_image" id="main_image" accept="image/*">
            <label for="main_image" class="file-input-label">
                <span style="font-size: 24px;">📷</span>
                <span>Klik untuk ganti gambar utama</span>
            </label>
        </div>
        <div class="helper-text">Kosongkan jika tidak ingin mengganti gambar</div>
        @error('main_image')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Galeri Gambar</label>
        @if($product->gallery_images && count($product->gallery_images) > 0)
            <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">
                @foreach($product->gallery_images as $image)
                    <img src="{{ asset('storage/' . $image) }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                @endforeach
            </div>
        @endif
        <div class="file-input-wrapper">
            <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple>
            <label for="gallery_images" class="file-input-label">
                <span style="font-size: 24px;">🖼️</span>
                <span>Klik untuk ganti galeri gambar</span>
            </label>
        </div>
        <div class="helper-text">Kosongkan jika tidak ingin mengganti galeri</div>
        @error('gallery_images.*')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <div class="checkbox-group">
            <input type="checkbox" name="is_featured" id="is_featured" {{ $product->is_featured ? 'checked' : '' }}>
            <label for="is_featured" style="margin: 0;">⭐ Jadikan Produk Featured</label>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">💾 Update Produk</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">❌ Batal</a>
    </div>
</form>
</div>
@endsection
````