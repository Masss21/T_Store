@extends('admin.layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<style>
    .form-container {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        max-width: 700px;
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
    textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s;
    }

    input:focus,
    textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    textarea {
        min-height: 100px;
        resize: vertical;
    }

    .current-image {
        margin-top: 10px;
    }

    .current-image img {
        max-width: 200px;
        border-radius: 10px;
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
</style>

<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.categories.index') }}" style="color: #667eea; text-decoration: none; font-weight: 600;">
        ← Kembali ke Daftar Kategori
    </a>
</div>

<div class="form-container">
    <h2 style="margin-bottom: 25px; color: #2c3e50;">✏️ Edit Kategori</h2>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama Kategori *</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea name="description" id="description">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Gambar Kategori</label>
            @if($category->image)
                <div class="current-image">
                    <p style="font-size: 14px; color: #7f8c8d; margin-bottom: 10px;">Gambar Saat Ini:</p>
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                </div>
            @endif
            <input type="file" name="image" id="image" accept="image/*" style="margin-top: 10px;">
            <p style="font-size: 12px; color: #7f8c8d; margin-top: 5px;">Kosongkan jika tidak ingin mengganti gambar</p>
            @error('image')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="checkbox-group">
                <input type="checkbox" name="is_active" id="is_active" {{ $category->is_active ? 'checked' : '' }}>
                <label for="is_active" style="margin: 0;">✓ Kategori Aktif</label>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Update Kategori</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">❌ Batal</a>
        </div>
    </form>
</div>
@endsection