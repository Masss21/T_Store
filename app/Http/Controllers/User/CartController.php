<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // View Cart Page
    public function index()
    {
        $cartItems = Cart::with('product.category')
            ->where('user_id', auth()->id())
            ->get();

        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        return view('user.cart.index', compact('cartItems', 'subtotal'));
    }

    // Add to Cart (AJAX)
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check stock
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi! Stok tersedia: ' . $product->stock,
            ], 400);
        }

        // Check max 25 items total
        $totalItemsInCart = Cart::where('user_id', auth()->id())
            ->sum('quantity');

        if ($totalItemsInCart >= 25) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang sudah penuh! Maksimal 25 item.',
            ], 400);
        }

        if (($totalItemsInCart + $request->quantity) > 25) {
            $available = 25 - $totalItemsInCart;
            return response()->json([
                'success' => false,
                'message' => "Anda hanya bisa menambah {$available} item lagi.",
            ], 400);
        }

        // Check if product already in cart
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $request->quantity;

            if ($newQuantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah melebihi stok yang tersedia!',
                ], 400);
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Add new item
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);
        }

        // Get updated cart count
        $cartCount = Cart::where('user_id', auth()->id())
            ->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang!',
            'cart_count' => $cartCount,
        ]);
    }

    // Update Quantity (AJAX)
    public function update(Request $request, Cart $cart)
    {
        // Authorization check
        if ($cart->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = $cart->product;

        // Check stock
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi!',
            ], 400);
        }

        // Check max 25 items
        $totalOtherItems = Cart::where('user_id', auth()->id())
            ->where('id', '!=', $cart->id)
            ->sum('quantity');

        if (($totalOtherItems + $request->quantity) > 25) {
            $maxAllowed = 25 - $totalOtherItems;
            return response()->json([
                'success' => false,
                'message' => "Maksimal {$maxAllowed} item untuk produk ini.",
            ], 400);
        }

        $cart->update(['quantity' => $request->quantity]);

        // Calculate new totals
        $subtotal = $cart->price * $cart->quantity;
        $cartTotal = Cart::where('user_id', auth()->id())
            ->get()
            ->sum(function($item) {
                return $item->price * $item->quantity;
            });

        return response()->json([
            'success' => true,
            'message' => 'Jumlah berhasil diupdate!',
            'subtotal' => $subtotal,
            'cart_total' => $cartTotal,
        ]);
    }

    // Remove Item (AJAX)
    public function remove(Cart $cart)
    {
        // Authorization check
        if ($cart->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $cart->delete();

        // Get updated cart count
        $cartCount = Cart::where('user_id', auth()->id())
            ->sum('quantity');

        $cartTotal = Cart::where('user_id', auth()->id())
            ->get()
            ->sum(function($item) {
                return $item->price * $item->quantity;
            });

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari keranjang!',
            'cart_count' => $cartCount,
            'cart_total' => $cartTotal,
        ]);
    }

    // Get Cart Count (for badge)
    public function count()
    {
        $count = Cart::where('user_id', auth()->id())
            ->sum('quantity');

        return response()->json([
            'count' => $count,
        ]);
    }
}