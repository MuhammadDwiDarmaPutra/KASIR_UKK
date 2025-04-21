<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk Bar Chart (Pendapatan Bulanan)
        $monthlyRevenue = Transaction::selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        // Data untuk Pie Chart (Distribusi Penjualan Produk)
        $productSales = Product::with('details')
            ->select('name')
            ->withSum('details', 'qty')
            ->get()
            ->pluck('details_sum_qty', 'name');

        // Data tambahan untuk kasir
        $count = Transaction::whereDate('created_at', now())->count();
        $member = Transaction::whereNotNull('member_id')->whereDate('created_at', now())->count();
        $nonMember = Transaction::whereNull('member_id')->whereDate('created_at', now())->count();
        $updated = Transaction::latest()->first();

        return view('dashboard', compact('monthlyRevenue', 'productSales', 'count', 'member', 'nonMember', 'updated'));
    }
}
