<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Jangan menimpa transaksi yang sudah ada (mis. hasil checkout user).
        if (Order::exists()) {
            return;
        }

        $orders = [
            ['code' => 'REC-048', 'email' => 'andy@email.com', 'name' => 'Andy Rafaela', 'status' => 'selesai', 'items' => [['abbey-road', 1]]],
            ['code' => 'REC-047', 'email' => 'budi@email.com', 'name' => 'Budi Santoso', 'status' => 'diproses', 'items' => [['blue-train', 1]]],
            ['code' => 'REC-046', 'email' => 'sari@email.com', 'name' => 'Sari Dewi', 'status' => 'menunggu', 'items' => [['the-album', 1], ['abbey-road', 1]]],
            ['code' => 'REC-045', 'email' => 'cahyo@email.com', 'name' => 'Cahyo Pratama', 'status' => 'dikirim', 'items' => [['born-pink', 1]]],
            ['code' => 'REC-044', 'email' => 'rina@email.com', 'name' => 'Rina Lestari', 'status' => 'dibatalkan', 'items' => [['savage', 1]]],
            ['code' => 'REC-043', 'email' => 'dodi@email.com', 'name' => 'Dodi Wijaya', 'status' => 'selesai', 'items' => [['spicy', 2]]],
            ['code' => 'REC-042', 'email' => 'user@recorda.com', 'name' => 'User Recorda', 'status' => 'selesai', 'items' => [['heavy-serenade', 1], ['drama', 1]]],
        ];

        foreach ($orders as $data) {
            $user = User::where('email', $data['email'])->first();

            $order = Order::updateOrCreate(
                ['code' => $data['code']],
                [
                    'user_id' => $user?->id,
                    'customer_name' => $data['name'],
                    'status' => $data['status'],
                    'total' => 0,
                ]
            );

            $order->items()->delete();
            $total = 0;

            foreach ($data['items'] as [$slug, $qty]) {
                $product = Product::where('slug', $slug)->first();
                if (! $product) {
                    continue;
                }

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'qty' => $qty,
                    'price' => $product->price,
                ]);

                $total += $product->price * $qty;
            }

            $order->update(['total' => $total]);
        }
    }
}
