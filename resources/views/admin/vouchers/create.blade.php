@extends('admin.layouts.app')

@section('title', 'Tambah Voucher')

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

    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #2196F3;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
    }

    .info-box h4 {
        color: #1976D2;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .info-box ul {
        margin: 0;
        padding-left: 20px;
        font-size: 13px;
        color: #555;
    }

    .info-box li {
        margin-bottom: 5px;
    }
</style>

<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.vouchers.index') }}" style="color: #f093fb; text-decoration: none; font-weight: 600;">
        ← Kembali ke Daftar Voucher
    </a>
</div>

<div class="form-container">
    <h2 style="margin-bottom: 25px; color: #2c3e50;">🎟️ Tambah Voucher Baru</h2>

    <div class="info-box">
        <h4>📌 Tipe Voucher:</h4>
        <ul>
            <li><strong>Diskon Persentase:</strong> Potongan berdasarkan % dari total belanja</li>
            <li><strong>Diskon Nominal:</strong> Potongan harga tetap dalam Rupiah</li>
            <li><strong>Cashback:</strong> Pengembalian dana setelah transaksi</li>
        </ul>
    </div>

    <form action="{{ route('admin.vouchers.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="code">Kode Voucher *</label>
            <input type="text" name="code" id="code" value="{{ old('code') }}" placeholder="Contoh: DISKON50K" required style="text-transform: uppercase;">
            <div class="helper-text">Gunakan huruf kapital dan angka (tanpa spasi)</div>
            @error('code')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="type">Tipe Voucher *</label>
                <select name="type" id="type" required onchange="updateValueLabel()">
                    <option value="">Pilih Tipe</option>
                    <option value="discount_percentage" {{ old('type') == 'discount_percentage' ? 'selected' : '' }}>Diskon Persentase (%)</option>
                    <option value="discount_fixed" {{ old('type') == 'discount_fixed' ? 'selected' : '' }}>Diskon Nominal (Rp)</option>
                    <option value="cashback" {{ old('type') == 'cashback' ? 'selected' : '' }}>Cashback (Rp)</option>
                </select>
                @error('type')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="value" id="value-label">Nilai *</label>
                <input type="number" name="value" id="value" value="{{ old('value') }}" min="0" step="0.01" required>
                <div class="helper-text" id="value-helper">Masukkan nilai voucher</div>
                @error('value')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="min_purchase">Minimal Pembelian (Rp) *</label>
                <input type="number" name="min_purchase" id="min_purchase" value="{{ old('min_purchase', 0) }}" min="0" required>
                <div class="helper-text">Minimal transaksi untuk menggunakan voucher</div>
                @error('min_purchase')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="max_discount">Maksimal Diskon (Rp)</label>
                <input type="number" name="max_discount" id="max_discount" value="{{ old('max_discount') }}" min="0">
                <div class="helper-text">Kosongkan jika tidak ada batas maksimal</div>
                @error('max_discount')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="usage_limit">Batas Penggunaan</label>
            <input type="number" name="usage_limit" id="usage_limit" value="{{ old('usage_limit') }}" min="1">
            <div class="helper-text">Kosongkan untuk unlimited penggunaan</div>
            @error('usage_limit')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="start_date">Tanggal Mulai *</label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
                @error('start_date')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="end_date">Tanggal Berakhir *</label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required>
                @error('end_date')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea name="description" id="description" placeholder="Jelaskan detail voucher, syarat & ketentuan...">{{ old('description') }}</textarea>
            @error('description')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="checkbox-group">
                <input type="checkbox" name="is_active" id="is_active" checked>
                <label for="is_active" style="margin: 0;">✓ Voucher Aktif</label>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Simpan Voucher</button>
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">❌ Batal</a>
        </div>
    </form>
</div>

<script>
function updateValueLabel() {
    const type = document.getElementById('type').value;
    const valueLabel = document.getElementById('value-label');
    const valueHelper = document.getElementById('value-helper');
    
    if (type === 'discount_percentage') {
        valueLabel.textContent = 'Nilai Diskon (%) *';
        valueHelper.textContent = 'Contoh: 10 untuk diskon 10%';
    } else if (type === 'discount_fixed') {
        valueLabel.textContent = 'Nilai Diskon (Rp) *';
        valueHelper.textContent = 'Contoh: 50000 untuk diskon Rp 50.000';
    } else if (type === 'cashback') {
        valueLabel.textContent = 'Nilai Cashback (Rp) *';
        valueHelper.textContent = 'Contoh: 25000 untuk cashback Rp 25.000';
    } else {
        valueLabel.textContent = 'Nilai *';
        valueHelper.textContent = 'Masukkan nilai voucher';
    }
}

// Auto uppercase code input
document.getElementById('code').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>
@endsection