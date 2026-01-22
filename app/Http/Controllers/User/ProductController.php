<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Detail Produk
    public function show($slug)
    {
        $product = Product::with(['category'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views
        $product->increment('views');

        // Related Products (dari kategori yang sama)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'available')
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('user.products.show', compact('product', 'relatedProducts'));
    }
}