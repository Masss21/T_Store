<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Show Checkout Page
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        if ($cartItems->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang Anda kosong!');
        }

        // Calculate subtotal
        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        // Shipping methods
        $shippingMethods = [
            ['id' => 'jne_regular', 'name' => 'JNE Regular', 'cost' => 15000, 'estimate' => '3-4 hari'],
            ['id' => 'jne_express', 'name' => 'JNE Express', 'cost' => 25000, 'estimate' => '1-2 hari'],
            ['id' => 'jnt_regular', 'name' => 'JNT Regular', 'cost' => 12000, 'estimate' => '3-5 hari'],
            ['id' => 'sicepat_halu', 'name' => 'SiCepat Halu', 'cost' => 10000, 'estimate' => '4-6 hari'],
        ];

        // Payment methods
        $paymentMethods = [
            ['id' => 'qris', 'name' => 'QRIS', 'icon' => '📱'],
            ['id' => 'bank_transfer', 'name' => 'Transfer Bank', 'icon' => '🏦'],
            ['id' => 'debit_card', 'name' => 'Kartu Debit', 'icon' => '💳'],
            ['id' => 'credit_card', 'name' => 'Kartu Kredit', 'icon' => '💳'],
            ['id' => 'e_wallet', 'name' => 'E-Wallet', 'icon' => '📲'],
        ];

        return view('user.checkout.index', compact(
            'cartItems',
            'subtotal',
            'shippingMethods',
            'paymentMethods'
        ));
    }

    // Apply Voucher
    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'subtotal' => 'required|numeric',
        ]);

        $voucher = Voucher::where('code', strtoupper($request->voucher_code))
            ->where('is_active', true)
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher tidak valid!',
            ], 400);
        }

        // Check date validity
        $now = now();
        if ($now->lt($voucher->start_date) || $now->gt($voucher->end_date)) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah tidak berlaku!',
            ], 400);
        }

        // Check usage limit
        if ($voucher->usage_limit && $voucher->usage_count >= $voucher->usage_limit) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah mencapai batas penggunaan!',
            ], 400);
        }

        // Check minimum purchase
        if ($request->subtotal < $voucher->min_purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal pembelian Rp ' . number_format($voucher->min_purchase, 0, ',', '.'),
            ], 400);
        }

        // Calculate discount
        $discount = 0;
        if ($voucher->type === 'discount_percentage') {
            $discount = ($request->subtotal * $voucher->value) / 100;
            
            // Check max discount
            if ($voucher->max_discount && $discount > $voucher->max_discount) {
                $discount = $voucher->max_discount;
            }
        } elseif ($voucher->type === 'discount_fixed') {
            $discount = $voucher->value;
        } elseif ($voucher->type === 'cashback') {
            $discount = $voucher->value;
        }

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil diterapkan!',
            'voucher' => [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'type' => $voucher->type,
                'discount' => $discount,
            ],
        ]);
    }

    // Place Order
    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_method' => 'required|string',
            'shipping_cost' => 'required|numeric',
            'payment_method' => 'required|in:qris,bank_transfer,debit_card,credit_card,e_wallet',
            'voucher_id' => 'nullable|exists:vouchers,id',
            'discount' => 'nullable|numeric',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            // Get cart items
            $cartItems = Cart::with('product')
                ->where('user_id', auth()->id())
                ->get();

            if ($cartItems->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang Anda kosong!',
                ], 400);
            }

            // Validate stock
            foreach ($cartItems as $item) {
                if ($item->product->stock < $item->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$item->product->name} tidak mencukupi!",
                    ], 400);
                }
            }

            // Generate order number
            $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => auth()->id(),
                'voucher_id' => $request->voucher_id,
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_method' => $request->shipping_method,
                'shipping_cost' => $request->shipping_cost,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'subtotal' => $request->subtotal,
                'discount' => $request->discount ?? 0,
                'total' => $request->total,
                'status' => 'pending',
            ]);

            // Create order items & reduce stock
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->price * $item->quantity,
                ]);

                // Reduce stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Update voucher usage
            if ($request->voucher_id) {
                Voucher::find($request->voucher_id)->increment('usage_count');
            }

            // Clear cart
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}