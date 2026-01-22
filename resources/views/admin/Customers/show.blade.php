@extends('layouts.app')

@section('title', 'Detail Pelanggan - ' . $customer->name)

@section('content')
<div class="animate__animated animate__fadeIn">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="font-size: 2rem;">👤 Detail Pelanggan</h1>
        <a href="{{ route('admin.customers.index') }}" class="btn" style="background: #f3f4f6;">
            ← Kembali
        </a>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
        <!-- Customer Info -->
        <div>
            <div class="card" style="margin-bottom: 2rem;">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 3rem; color: white;">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </div>
                    <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem;">{{ $customer->name }}</h2>
                    <p style="color: #6b7280;">ID: #{{ $customer->id }}</p>
                </div>
                
                <div style="border-top: 1px solid #e5e7eb; padding-top: 1.5rem;">
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">📧 Email</div>
                        <div style="font-weight: 600;">{{ $customer->email }}</div>
                    </div>
                    
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">📱 Telepon</div>
                        <div style="font-weight: 600;">{{ $customer->phone ?? '-' }}</div>
                    </div>
                    
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">📅 Terdaftar</div>
                        <div style="font-weight: 600;">{{ $customer->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">✅ Status</div>
                        <span style="padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600; display: inline-block;
                            {{ $customer->email_verified_at ? 'background: #d1fae5; color: #065f46;' : 'background: #fee2e2; color: #991b1b;' }}">
                            {{ $customer->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="card">
                <h3 style="margin-bottom: 1rem;">⚙️ Aksi</h3>
                
                <button onclick="resetPassword()" class="btn btn-secondary" style="width: 100%; margin-bottom: 0.5rem;">
                    🔑 Reset Password
                </button>
                
                @if($customer->email_verified_at)
                    <button onclick="suspendCustomer()" class="btn btn-danger" style="width: 100%; margin-bottom: 0.5rem;">
                        🚫 Suspend Akun
                    </button>
                @else
                    <button onclick="activateCustomer()" class="btn" style="width: 100%; margin-bottom: 0.5rem; background: var(--secondary); color: white;">
                        ✅ Aktivasi Akun
                    </button>
                @endif
                
                <form action="{{ route('admin.customers.destroy', $customer) }}" 
                      method="POST" 
                      onsubmit="return confirm('Yakin hapus pelanggan ini? Semua data akan terhapus!')"
                      style="margin-top: 1rem;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width: 100%;">
                        🗑️ Hapus Pelanggan
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Statistics & Orders -->
        <div>
            <!-- Stats -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem;">
                <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🛍️</div>
                    <div style="font-size: 2rem; font-weight: bold;">{{ $stats['total_orders'] }}</div>
                    <div style="opacity: 0.9; font-size: 0.875rem;">Total Pesanan</div>
                </div>
                
                <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">💰</div>
                    <div style="font-size: 1.2rem; font-weight: bold;">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</div>
                    <div style="opacity: 0.9; font-size: 0.875rem;">Total Belanja</div>
                </div>
                
                <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">✅</div>
                    <div style="font-size: 2rem; font-weight: bold;">{{ $stats['completed_orders'] }}</div>
                    <div style="opacity: 0.9; font-size: 0.875rem;">Selesai</div>
                </div>
            </div>
            
            <!-- Order Status -->
            <div class="card" style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1rem;">📊 Status Pesanan</h3>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280;">Pending</div>
                        <div style="font-size: 1.5rem; font-weight: bold; color: #f59e0b;">{{ $stats['pending_orders'] }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280;">Completed</div>
                        <div style="font-size: 1.5rem; font-weight: bold; color: #10b981;">{{ $stats['completed_orders'] }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280;">Cancelled</div>
                        <div style="font-size: 1.5rem; font-weight: bold; color: #ef4444;">{{ $stats['cancelled_orders'] }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="card" style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1rem;">🛍️ Riwayat Pesanan</h3>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f3f4f6; text-align: left;">
                                <th style="padding: 0.75rem;">Order Number</th>
                                <th style="padding: 0.75rem;">Total</th>
                                <th style="padding: 0.75rem;">Status</th>
                                <th style="padding: 0.75rem;">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->orders()->latest()->take(10)->get() as $order)
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 0.75rem; font-weight: 600;">{{ $order->order_number }}</td>
                                    <td style="padding: 0.75rem; font-weight: bold; color: var(--primary);">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <span style="padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600;
                                            @if($order->status == 'completed') background: #d1fae5; color: #065f46;
                                            @elseif($order->status == 'pending') background: #fef3c7; color: #92400e;
                                            @elseif($order->status == 'cancelled') background: #fee2e2; color: #991b1b;
                                            @else background: #dbeafe; color: #1e40af;
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td style="padding: 0.75rem; color: #6b7280; font-size: 0.875rem;">
                                        {{ $order->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="padding: 2rem; text-align: center; color: #6b7280;">
                                        Belum ada pesanan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Cart & Wishlist -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="card">
                    <h3 style="margin-bottom: 1rem;">🛒 Keranjang ({{ $stats['items_in_cart'] }})</h3>
                    @forelse($customer->carts->take(5) as $cart)
                        <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <div>{{ $cart->quantity }}x</div>
                            <div style="color: #6b7280;">{{ $cart->product->name }}</div>
                        </div>
                    @empty
                        <p style="color: #6b7280; font-size: 0.875rem;">Keranjang kosong</p>
                    @endforelse
                </div>
                
                <div class="card">
                    <h3 style="margin-bottom: 1rem;">❤️ Wishlist ({{ $stats['items_in_wishlist'] }})</h3>
                    @forelse($customer->wishlists->take(5) as $wishlist)
                        <div style="margin-bottom: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                            {{ $wishlist->product->name }}
                        </div>
                    @empty
                        <p style="color: #6b7280; font-size: 0.875rem;">Wishlist kosong</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function resetPassword() {
        Swal.fire({
            title: 'Reset Password',
            html: `
                <input type="password" id="new_password" class="form-control" placeholder="Password baru (min 8 karakter)" style="margin-bottom: 1rem;">
                <input type="password" id="new_password_confirmation" class="form-control" placeholder="Konfirmasi password">
            `,
            showCancelButton: true,
            confirmButtonText: 'Reset',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const password = document.getElementById('new_password').value;
                const confirmation = document.getElementById('new_password_confirmation').value;
                
                if (!password || password.length < 8) {
                    Swal.showValidationMessage('Password minimal 8 karakter');
                    return false;
                }
                
                if (password !== confirmation) {
                    Swal.showValidationMessage('Password tidak cocok');
                    return false;
                }
                
                return { password, confirmation };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post('{{ route('admin.customers.reset-password', $customer) }}', {
                    new_password: result.value.password,
                    new_password_confirmation: result.value.confirmation
                })
                .then(response => {
                    showToast(response.data.message, 'success');
                })
                .catch(error => {
                    showToast('Gagal reset password', 'error');
                });
            }
        });
    }
    
    function suspendCustomer() {
        Swal.fire({
            title: 'Suspend Akun?',
            text: 'Pelanggan tidak akan bisa login setelah di-suspend',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Suspend',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ef4444'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post('{{ route('admin.customers.update-status', $customer) }}', {
                    status: 'suspended'
                })
                .then(response => {
                    showToast(response.data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                });
            }
        });
    }
    
    function activateCustomer() {
        axios.post('{{ route('admin.customers.update-status', $customer) }}', {
            status: 'active'
        })
        .then(response => {
            showToast(response.data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        });
    }
</script>
@endpush
@endsection