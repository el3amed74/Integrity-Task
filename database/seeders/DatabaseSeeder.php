<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->create();

        // Create 10 products
        Product::factory(10)->create();

        // Create 5 orders with items
        Order::factory(5)->create()->each(function ($order) {
            // Attach 2–4 random products to each order
            $products = Product::inRandomOrder()->take(rand(2, 4))->get();

            $total = 0;

            foreach ($products as $product) {
                $qty = rand(1, 3);
                $subtotal = $product->price * $qty;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $order->update(['total' => $total]);
        });
    }
}
