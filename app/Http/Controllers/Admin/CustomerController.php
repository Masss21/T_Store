<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->withCount(['orders', 'carts', 'wishlists']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by verification status
        if ($request->has('verified')) {
            if ($request->verified == 'yes') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->verified == 'no') {
                $query->whereNull('email_verified_at');
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $customers = $query->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Display the specified customer
     */
    public function show(User $customer)
    {
        // Pastikan yang ditampilkan adalah user, bukan admin
        if ($customer->role !== 'user') {
            abort(403, 'Unauthorized');
        }

        // Load relationships dengan nama yang benar
        $customer->load(['orders.orderItems.product', 'wishlists.product', 'carts.product']);
        
        $stats = [
            'total_orders' => $customer->orders()->count(),
            'total_spent' => $customer->orders()->where('status', 'completed')->sum('total'),
            'pending_orders' => $customer->orders()->where('status', 'pending')->count(),
            'completed_orders' => $customer->orders()->where('status', 'completed')->count(),
            'cancelled_orders' => $customer->orders()->where('status', 'cancelled')->count(),
            'items_in_cart' => $customer->carts()->count(),
            'items_in_wishlist' => $customer->wishlists()->count(),
        ];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    /**
     * Update customer status
     */
    public function updateStatus(Request $request, User $customer)
    {
        $request->validate([
            'status' => 'required|in:active,suspended'
        ]);

        // Gunakan field custom atau update berdasarkan kebutuhan
        // Untuk demo, kita bisa set email_verified_at
        if ($request->status == 'suspended') {
            $customer->update(['email_verified_at' => null]);
        } else {
            $customer->update(['email_verified_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pelanggan berhasil diupdate'
        ]);
    }

    /**
     * Delete customer
     */
    public function destroy(User $customer)
    {
        // Pastikan yang dihapus adalah user, bukan admin
        if ($customer->role !== 'user') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus admin');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
                        ->with('success', 'Pelanggan berhasil dihapus');
    }

    /**
     * Reset customer password
     */
    public function resetPassword(Request $request, User $customer)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed'
        ]);

        $customer->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset'
        ]);
    }

    /**
     * Export customers to CSV
     */
    public function export()
    {
        $customers = User::where('role', 'user')
                        ->withCount('orders')
                        ->get();

        $filename = 'customers_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['ID', 'Nama', 'Email', 'Telepon', 'Total Pesanan', 'Terverifikasi', 'Tanggal Daftar']);
            
            // Data
            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->id,
                    $customer->name,
                    $customer->email,
                    $customer->phone,
                    $customer->orders_count,
                    $customer->email_verified_at ? 'Ya' : 'Tidak',
                    $customer->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}