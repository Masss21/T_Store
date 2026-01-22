<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Models\Voucher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        // Statistics
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total');
        
        // Recent Orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();
        
        // Low Stock Products
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();
        
        // Out of Stock
        $outOfStockCount = Product::where('stock', 0)->count();
        
        // Categories Count
        $totalCategories = Category::count();
        
        // Active Vouchers
        try {
            $activeVouchers = Voucher::where('is_active', true)
                ->where('valid_until', '>=', now())
                ->count();
        } catch (\Exception $e) {
            // Fallback jika kolom valid_until tidak ada
            $activeVouchers = Voucher::where('is_active', true)->count();
        }
        
        // Orders by Status
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        
        // Monthly Revenue (current month)
        $monthlyRevenue = Order::where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total');
        
        // Today's Orders
        $todayOrders = Order::whereDate('created_at', today())->count();
        
        // New Users This Month
        $newUsersThisMonth = User::where('role', 'user')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'recentOrders',
            'lowStockProducts',
            'outOfStockCount',
            'totalCategories',
            'activeVouchers',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'cancelledOrders',
            'monthlyRevenue',
            'todayOrders',
            'newUsersThisMonth'
        ));
    }
}