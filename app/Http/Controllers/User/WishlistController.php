<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // View Wishlist Page
    public function index()
    {
        $wishlistItems = Wishlist::with('product.category')
            ->where('user_id', auth()->id())
            ->get();

        return view('user.wishlist.index', compact('wishlistItems'));
    }

    // Add to Wishlist (AJAX)
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Check if already in wishlist
        $exists = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Produk sudah ada di wishlist!',
            ], 400);
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ]);

        $wishlistCount = Wishlist::where('user_id', auth()->id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke wishlist!',
            'wishlist_count' => $wishlistCount,
        ]);
    }

    // Remove from Wishlist (AJAX)
    public function remove(Wishlist $wishlist)
    {
        // Authorization check
        if ($wishlist->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $wishlist->delete();

        $wishlistCount = Wishlist::where('user_id', auth()->id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari wishlist!',
            'wishlist_count' => $wishlistCount,
        ]);
    }

    // Move to Cart
    public function moveToCart(Wishlist $wishlist)
    {
        // Authorization check
        if ($wishlist->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $product = $wishlist->product;

        // Check stock
        if ($product->stock < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Produk sedang tidak tersedia!',
            ], 400);
        }

        // Check max 25 items in cart
        $totalItemsInCart = Cart::where('user_id', auth()->id())->sum('quantity');

        if ($totalItemsInCart >= 25) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang sudah penuh! Maksimal 25 item.',
            ], 400);
        }

        // Check if already in cart
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Just increment quantity
            if ($cartItem->quantity < $product->stock) {
                $cartItem->increment('quantity');
            }
        } else {
            // Add to cart
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
            ]);
        }

        // Remove from wishlist
        $wishlist->delete();

        $wishlistCount = Wishlist::where('user_id', auth()->id())->count();
        $cartCount = Cart::where('user_id', auth()->id())->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dipindahkan ke keranjang!',
            'wishlist_count' => $wishlistCount,
            'cart_count' => $cartCount,
        ]);
    }

    // Get Wishlist Count
    public function count()
    {
        $count = Wishlist::where('user_id', auth()->id())->count();

        return response()->json([
            'count' => $count,
        ]);
    }
}