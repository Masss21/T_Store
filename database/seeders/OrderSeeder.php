<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $products = Product::all();

        if ($users->count() == 0 || $products->count() == 0) {
            $this->command->warn('⚠️ Tidak ada user atau produk untuk membuat sample order');
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            $user = $users->random();
            $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'shipping_name' => $user->name,
                'shipping_phone' => $user->phone,
                'shipping_address' => $user->address ?? 'Jl. Sample No. 123, Jakarta',
                'shipping_method' => collect(['JNE Regular', 'JNT Express', 'SiCepat Halu'])->random(),
                'shipping_cost' => rand(10, 50) * 1000,
                'payment_method' => collect(['qris', 'debit_card', 'e_wallet', 'bank_transfer'])->random(),
                'payment_status' => collect(['pending', 'paid', 'paid', 'paid'])->random(),
                'subtotal' => 0,
                'discount' => 0,
                'total' => 0,
                'status' => collect(['pending', 'processing', 'shipped', 'delivered'])->random(),
                'paid_at' => rand(0, 1) ? now()->subDays(rand(1, 5)) : null,
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            $subtotal = 0;
            $itemCount = rand(1, 4);
            
            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $itemSubtotal = $product->price * $quantity;
                $subtotal += $itemSubtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal,
                ]);
            }

            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal + $order->shipping_cost,
            ]);
        }

        $this->command->info('✅ 10 sample orders berhasil dibuat!');
    }
}