<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the home page with products
     * Accessible by both guest and authenticated users
     */
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return view('user.home.index', compact('featuredProducts', 'categories'));
    }

    /**
     * Search products
     * Accessible by both guest and authenticated users
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $products = Product::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->paginate(12);

        return view('user.home.search', compact('products', 'query'));
    }
}