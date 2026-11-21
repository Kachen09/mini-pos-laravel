<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Product;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $filter = $request->input('filter', 'today');

        $query = Sale::query();

        if ($filter == 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter == 'this_month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($filter == 'this_year') {
            $query->whereYear('created_at', Carbon::now()->year);
        }

        $sales = $query->orderBy('created_at', 'desc')->get();

        $totalSales = $sales->sum('grand_total');

        return view('reports.sales', compact('sales', 'totalSales', 'filter'));
    }

    public function bestSellersReport()
    {
        $bestSellers = SaleDetail::select(
                'sale_items.product_name', 
                'products.image',        
                DB::raw('SUM(sale_items.qty) as total_sold')
            )

            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->groupBy('sale_items.product_name', 'products.image')
            ->orderBy('total_sold', 'desc')
            ->take(10)
            ->get();

        return view('reports.best_sellers', compact('bestSellers'));
    }

    public function lowStockReport()
    {
        $lowStockProducts = Product::whereColumn('stock', '<=', 'reorder_level')
                                  ->orderBy('stock', 'asc')
                                  ->get();

        return view('reports.low_stock', compact('lowStockProducts'));
    }

    public function dashboard()
    {
        $today = Carbon::today();

        // 1. ยอดขายรวมวันนี้
        $todaySalesTotal = Sale::whereDate('created_at', $today)->sum('grand_total');

        // 2. จำนวนบิลวันนี้
        $todayOrdersCount = Sale::whereDate('created_at', $today)->count();

        // 3. สินค้าขายดี 3 อันดับแรกวันนี้
        $todayBestSellers = SaleDetail::select('product_name', DB::raw('SUM(qty) as total_sold'))
                            // ▼▼▼ แก้ไขชื่อตารางตรงนี้ ▼▼▼
                            ->whereDate('sale_items.created_at', $today) 
                            // ▲▲▲ สิ้นสุดส่วนที่แก้ไข ▲▲▲
                            ->groupBy('product_name')
                            ->orderBy('total_sold', 'desc')
                            ->take(3) 
                            ->get();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $monthSalesTotal = Sale::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('grand_total');
        $monthOrdersCount = Sale::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        $lowStockCount = Product::whereColumn('stock', '<=', 'reorder_level')->count();

        return view('dashboard', compact(
            'todaySalesTotal',
            'todayOrdersCount',
            'todayBestSellers',
            'monthSalesTotal',
            'monthOrdersCount',
            'lowStockCount'
        ));
    }
}