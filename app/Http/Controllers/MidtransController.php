<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    /**
     * Webhook notifikasi dari server Midtrans (HTTP Notification URL).
     * Memperbarui status order berdasarkan status transaksi.
     */
    public function notification(Request $request, MidtransService $midtrans)
    {
        $payload = $request->all();

        if (! $midtrans->verifySignature($payload)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('code', $payload['order_id'] ?? '')->first();

        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $status = $midtrans->mapStatus($payload);

        if ($status) {
            $order->update(['status' => $status]);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Halaman yang dituju setelah user menyelesaikan pembayaran di Midtrans.
     */
    public function finish(Request $request)
    {
        $status = $request->query('transaction_status');

        $message = match ($status) {
            'settlement', 'capture' => 'Pembayaran berhasil. Terima kasih!',
            'pending' => 'Pembayaran sedang menunggu konfirmasi.',
            'deny', 'cancel', 'expire' => 'Pembayaran dibatalkan / gagal.',
            default => 'Status pembayaran sedang diproses.',
        };

        return redirect()->route('recorda.history')->with('success', $message);
    }
}
