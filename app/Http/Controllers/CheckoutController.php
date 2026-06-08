<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('pages.checkout');
    }

    public function store(Request $request, MidtransService $midtrans)
    {
        $request->validate([
            'items' => ['required', 'string'],
        ]);

        $items = json_decode($request->input('items'), true);

        if (! is_array($items) || count($items) === 0) {
            return back()->with('error', 'Keranjang kosong.');
        }

        $user = $request->user();

        $order = DB::transaction(function () use ($items, $user, $request) {
            $order = Order::create([
                'code' => $this->generateCode(),
                'user_id' => $user?->id,
                'customer_name' => $user?->name ?? $request->input('customer_name', 'Tamu'),
                'total' => 0,
                'status' => 'menunggu',
            ]);

            $total = 0;

            foreach ($items as $item) {
                $slug = $item['slug'] ?? null;
                $qty = max(1, (int) ($item['qty'] ?? 1));
                $product = $slug ? Product::where('slug', $slug)->first() : null;

                $name = $product->name ?? ($item['name'] ?? 'Produk');
                $price = $product->price ?? (int) ($item['price'] ?? 0);

                $order->items()->create([
                    'product_id' => $product?->id,
                    'product_name' => $name,
                    'qty' => $qty,
                    'price' => $price,
                ]);

                if ($product) {
                    $product->decrement('stock', min($qty, $product->stock));
                }

                $total += $price * $qty;
            }

            $order->update(['total' => $total]);

            return $order;
        });

        // Jika Midtrans aktif, arahkan ke halaman pembayaran Midtrans (Snap).
        if ($midtrans->isConfigured()) {
            $order->loadMissing('items');
            $redirectUrl = $midtrans->createSnapRedirect($order, $user?->email);

            if ($redirectUrl) {
                return redirect()->away($redirectUrl);
            }

            // Gagal membuat transaksi Midtrans: tetap simpan order, beri tahu user.
            return redirect()
                ->route('recorda.history')
                ->with('error', 'Pesanan ' . $order->code . ' dibuat, tetapi gagal memulai pembayaran. Coba lagi dari riwayat.');
        }

        return redirect()
            ->route('recorda.history')
            ->with('success', 'Pesanan ' . $order->code . ' berhasil dibuat.');
    }

    private function generateCode(): string
    {
        $last = Order::orderByDesc('id')->value('id') ?? 0;

        return 'REC-' . str_pad((string) ($last + 1), 3, '0', STR_PAD_LEFT);
    }
}
