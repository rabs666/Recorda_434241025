<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function isConfigured(): bool
    {
        return ! empty(config('services.midtrans.server_key'));
    }

    private function isProduction(): bool
    {
        return (bool) config('services.midtrans.is_production');
    }

    private function snapUrl(): string
    {
        return $this->isProduction()
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    /**
     * Buat transaksi Snap untuk sebuah Order. Mengembalikan redirect_url, atau null jika gagal.
     */
    public function createSnapRedirect(Order $order, ?string $email): ?string
    {
        $items = $order->items->map(fn ($item) => [
            'id' => (string) ($item->product_id ?? $item->id),
            'price' => (int) $item->price,
            'quantity' => (int) $item->qty,
            'name' => mb_substr($item->product_name, 0, 50),
        ])->values()->all();

        $payload = [
            'transaction_details' => [
                'order_id' => $order->code,
                'gross_amount' => (int) $order->total,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $email ?: 'customer@recorda.test',
            ],
            'callbacks' => [
                'finish' => route('recorda.payment.finish'),
            ],
        ];

        try {
            $response = Http::withBasicAuth(config('services.midtrans.server_key'), '')
                ->acceptJson()
                ->asJson()
                ->post($this->snapUrl(), $payload);

            if ($response->successful()) {
                return $response->json('redirect_url');
            }

            Log::warning('Midtrans snap gagal', ['status' => $response->status(), 'body' => $response->body()]);
        } catch (\Throwable $e) {
            Log::error('Midtrans error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Verifikasi signature dari notifikasi Midtrans.
     */
    public function verifySignature(array $payload): bool
    {
        $expected = hash('sha512',
            ($payload['order_id'] ?? '') .
            ($payload['status_code'] ?? '') .
            ($payload['gross_amount'] ?? '') .
            config('services.midtrans.server_key')
        );

        return hash_equals($expected, $payload['signature_key'] ?? '');
    }

    /**
     * Petakan status transaksi Midtrans ke status order lokal.
     */
    public function mapStatus(array $payload): ?string
    {
        $status = $payload['transaction_status'] ?? '';
        $fraud = $payload['fraud_status'] ?? 'accept';

        return match ($status) {
            'capture' => $fraud === 'challenge' ? 'menunggu' : 'diproses',
            'settlement' => 'diproses',
            'pending' => 'menunggu',
            'deny', 'cancel', 'expire', 'failure' => 'dibatalkan',
            default => null,
        };
    }
}
