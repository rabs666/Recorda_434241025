<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $revenue = Order::where('status', 'selesai')->sum('total');
        $orderCount = Order::count();
        $userCount = User::count();
        $activeProducts = Product::where('is_active', true)->count();

        $topProducts = OrderItem::select('product_name', DB::raw('SUM(qty) as sold'))
            ->groupBy('product_name')
            ->orderByDesc('sold')
            ->take(4)
            ->get();

        $maxSold = $topProducts->max('sold') ?: 1;

        $recentOrders = Order::orderByDesc('created_at')->take(4)->get();

        return view('pages.dashboard', compact(
            'revenue', 'orderCount', 'userCount', 'activeProducts',
            'topProducts', 'maxSold', 'recentOrders'
        ));
    }
}
