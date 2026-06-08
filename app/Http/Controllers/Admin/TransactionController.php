<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('status', 'all');

        $orders = Order::with('items')
            ->when($filter !== 'all', fn ($q) => $q->where('status', $filter))
            ->orderByDesc('created_at')
            ->get();

        return view('pages.admin-transactions', compact('orders', 'filter'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:menunggu,diproses,dikirim,selesai,dibatalkan'],
        ]);

        $order->update(['status' => $request->input('status')]);

        return back()->with('success', 'Status transaksi #' . $order->code . ' diperbarui.');
    }
}
