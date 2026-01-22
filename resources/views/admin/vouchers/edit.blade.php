@extends('admin.layouts.app')

@section('title', 'Edit Voucher')

@section('content')
<style>
    .form-container {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        max-width: 800px;
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
    input[type="date"],
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
        border-color: #f093fb;
        box-shadow: 0 0 0 3px rgba(240, 147, 251, 0.1);
    }

    textarea {
        min-height: 80px;
        resize: vertical;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .helper-text {
        font-size: 12px;
        color: #7f8c8d;
        margin-top: 5px;
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
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(240, 147, 251, 0.4);
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

    .usage-info {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
    }
</style>

<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.vouchers.index') }}" style="color: #f093fb; text-decoration: none; font-weight: 600;">
        ← Kembali ke Daftar Voucher
    </a>
</div>

<div class="form-container">
    <h2 style="margin-bottom: 25px; color: #2c3e50;">✏️ Edit Voucher</h2>

    @if($voucher->usage_count > 0)
    <div class="usage-info">
        <strong>⚠️ Informasi:</strong> Voucher ini sudah digunakan <strong>{{ $voucher->usage_count }}</strong> kali.
    </div>
    @endif

    <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="code">Kode Voucher *</label>
            <input type="text" name="code" id="code" value="{{ old('code', $voucher->code) }}" required style="text-transform: uppercase;">
            @error('code')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="type">Tipe Voucher *</label>
                <select name="type" id="type" required>
                    <option value="discount_percentage" {{ $voucher->type == 'discount_percentage' ? 'selected' : '' }}>Diskon Persentase (%)</option>
                    <option value="discount_fixed" {{ $voucher->type == 'discount_fixed' ? 'selected' : '' }}>Diskon Nominal (Rp)</option>
                    <option value="cashback" {{ $voucher->type == 'cashback' ? 'selected' : '' }}>Cashback (Rp)</option>
                </select>
                @error('type')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="value">Nilai *</label>
                <input type="number" name="value" id="value" value="{{ old('value', $voucher->value) }}" min="0" step="0.01" required>
                @error('value')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="min_purchase">Minimal Pembelian (Rp) *</label>
                <input type="number" name="min_purchase" id="min_purchase" value="{{ old('min_purchase', $voucher->min_purchase) }}" min="0" required>
                @error('min_purchase')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="max_discount">Maksimal Diskon (Rp)</label>
                <input type="number" name="max_discount" id="max_discount" value="{{ old('max_discount', $voucher->max_discount) }}" min="0">
                @error('max_discount')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label for="usage_limit">Batas Penggunaan</label>
        <input type="number" name="usage_limit" id="usage_limit" value="{{ old('usage_limit', $voucher->usage_limit) }}" min="{{ $voucher->usage_count }}">
        <div class="helper-text">Sudah digunakan: {{ $voucher->usage_count }} kali</div>
        @error('usage_limit')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="start_date">Tanggal Mulai *</label>
            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $voucher->start_date) }}" required>
            @error('start_date')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="end_date">Tanggal Berakhir *</label>
            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $voucher->end_date) }}" required>
            @error('end_date')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label for="description">Deskripsi</label>
        <textarea name="description" id="description">{{ old('description', $voucher->description) }}</textarea>
        @error('description')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <div class="checkbox-group">
            <input type="checkbox" name="is_active" id="is_active" {{ $voucher->is_active ? 'checked' : '' }}>
            <label for="is_active" style="margin: 0;">✓ Voucher Aktif</label>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">💾 Update Voucher</button>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">❌ Batal</a>
    </div>
</form>
</div>
<script>
document.getElementById('code').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>
@endsection