@extends('admin.layouts.app')

@section('title', 'Kelola Voucher')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        box-shadow: 0 5px 20px rgba(240, 147, 251, 0.4);
    }

    .vouchers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
    }

    .voucher-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
        animation: fadeInUp 0.5s ease;
        border-left: 5px solid #f093fb;
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

    .voucher-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .voucher-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .voucher-code {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        font-family: 'Courier New', monospace;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .voucher-type {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .type-percentage {
        background: #fff3cd;
        color: #856404;
    }

    .type-fixed {
        background: #d4edda;
        color: #155724;
    }

    .type-cashback {
        background: #d1ecf1;
        color: #0c5460;
    }

    .voucher-value {
        font-size: 32px;
        font-weight: 700;
        color: #e74c3c;
        margin: 15px 0;
    }

    .voucher-info {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        margin: 15px 0;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .info-row:last-child {
        margin-bottom: 0;
    }

    .info-label {
        color: #7f8c8d;
    }

    .info-value {
        color: #2c3e50;
        font-weight: 600;
    }

    .voucher-dates {
        display: flex;
        gap: 15px;
        margin: 15px 0;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .date-item {
        flex: 1;
        text-align: center;
    }

    .date-label {
        font-size: 11px;
        color: #7f8c8d;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .date-value {
        font-size: 13px;
        color: #2c3e50;
        font-weight: 600;
    }

    .usage-bar {
        margin: 15px 0;
    }

    .usage-label {
        font-size: 12px;
        color: #7f8c8d;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);
        transition: width 0.3s;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        margin-top: 10px;
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
        margin-top: 15px;
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
    <h2 style="color: #2c3e50; font-size: 24px;">🎟️ Kelola Voucher</h2>
    <a href="{{ route('admin.vouchers.create') }}" class="btn-primary">
        <span>➕</span>
        <span>Tambah Voucher</span>
    </a>
</div>

<div class="vouchers-grid">
    @forelse($vouchers as $voucher)
    <div class="voucher-card">
        <div class="voucher-header">
            <div class="voucher-code">{{ $voucher->code }}</div>
            @if($voucher->type == 'discount_percentage')
                <span class="voucher-type type-percentage">% Persentase</span>
            @elseif($voucher->type == 'discount_fixed')
                <span class="voucher-type type-fixed">💰 Nominal</span>
            @else
                <span class="voucher-type type-cashback">💸 Cashback</span>
            @endif
        </div>

        <div class="voucher-value">
            @if($voucher->type == 'discount_percentage')
                {{ $voucher->value }}%
            @else
                Rp {{ number_format($voucher->value, 0, ',', '.') }}
            @endif
        </div>

        <div class="voucher-info">
            <div class="info-row">
                <span class="info-label">Min. Pembelian:</span>
                <span class="info-value">Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</span>
            </div>
            @if($voucher->max_discount)
            <div class="info-row">
                <span class="info-label">Max. Diskon:</span>
                <span class="info-value">Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>

        <div class="voucher-dates">
            <div class="date-item">
                <div class="date-label">Mulai</div>
                <div class="date-value">{{ \Carbon\Carbon::parse($voucher->start_date)->format('d M Y') }}</div>
            </div>
            <div class="date-item">
                <div class="date-label">Berakhir</div>
                <div class="date-value">{{ \Carbon\Carbon::parse($voucher->end_date)->format('d M Y') }}</div>
            </div>
        </div>

        @if($voucher->usage_limit)
        <div class="usage-bar">
            <div class="usage-label">
                <span>Penggunaan</span>
                <span><strong>{{ $voucher->usage_count }}</strong> / {{ $voucher->usage_limit }}</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ ($voucher->usage_count / $voucher->usage_limit) * 100 }}%"></div>
            </div>
        </div>
        @else
        <div class="usage-label">
            <span>Penggunaan</span>
            <span><strong>{{ $voucher->usage_count }}</strong> / Unlimited</span>
        </div>
        @endif

        <div>
            @php
                $now = now();
                $start = \Carbon\Carbon::parse($voucher->start_date);
                $end = \Carbon\Carbon::parse($voucher->end_date);
            @endphp

            @if(!$voucher->is_active)
                <span class="badge badge-danger">✗ Nonaktif</span>
            @elseif($now->lt($start))
                <span class="badge badge-warning">⏳ Belum Dimulai</span>
            @elseif($now->gt($end))
                <span class="badge badge-danger">⏰ Kadaluarsa</span>
            @elseif($voucher->usage_limit && $voucher->usage_count >= $voucher->usage_limit)
                <span class="badge badge-danger">🚫 Limit Tercapai</span>
            @else
                <span class="badge badge-success">✓ Aktif</span>
            @endif
        </div>

        @if($voucher->description)
        <p style="font-size: 13px; color: #7f8c8d; margin-top: 10px; line-height: 1.5;">
            {{ Str::limit($voucher->description, 80) }}
        </p>
        @endif

        <div class="action-buttons">
            <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn-sm btn-edit">✏️ Edit</a>
           <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" style="flex: 1;"
      onsubmit="confirmDelete(event, 'voucher {{ $voucher->code }}')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-sm btn-delete" style="width: 100%;">🗑️ Hapus</button>
</form>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #7f8c8d;">
        <div style="font-size: 64px; margin-bottom: 20px;">🎟️</div>
        <h3>Belum Ada Voucher</h3>
        <p>Mulai buat voucher untuk menarik lebih banyak pelanggan</p>
    </div>
    @endforelse
</div>

@if($vouchers->hasPages())
<div style="margin-top: 30px; display: flex; justify-content: center;">
    {{ $vouchers->links() }}
</div>
@endif
@endsection