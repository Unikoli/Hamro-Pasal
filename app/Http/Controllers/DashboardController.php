<?php

// namespace App\Http\Controllers;

// use App\Models\Product;
// use App\Models\Sale;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Http;
// use Carbon\Carbon;

// class DashboardController extends Controller
// {
//     public function index()
//     {
//         // === Existing Statistics ===
        
//         $totalProducts = Product::count();
//         $totalSales = $this->getTotalSalesRevenue();
//         $todaySales = $this->getTodaySalesRevenue();
//         $lowStockProducts = Product::where('current_stock', '<=', DB::raw('reorder_level'))->count();

//         // === Chart Data ===
//         $monthlySalesData = $this->getMonthlySalesData();
//         $dailySalesData = $this->getDailySalesData();

//         // === Recent Sales ===
//         $recentSales = Sale::with(['product', 'user'])
//             ->orderBy('created_at', 'desc')
//             ->limit(5)
//             ->get()
//             ->map(function ($sale) {
//                 return [
//                     'id' => $sale->id,
//                     'product_name' => $sale->product->name,
//                     'quantity' => $sale->quantity,
//                     'unit_price' => $sale->product->price,
//                     'total_amount' => $sale->quantity * $sale->product->price,
//                     'user_name' => $sale->user->name,
//                     'created_at' => $sale->created_at->format('M d, Y H:i'),
//                 ];
//             });

//         // === Top Products ===
//         $topProducts = DB::table('sales')
//             ->join('products', 'sales.product_id', '=', 'products.id')
//             ->select(
//                 'products.name',
//                 DB::raw('SUM(sales.quantity) as total_quantity'),
//                 DB::raw('SUM(sales.quantity * products.price) as total_revenue')
//             )
//             ->groupBy('products.id', 'products.name')
//             ->orderBy('total_revenue', 'desc')
//             ->limit(5)
//             ->get();

//         // === Low Stock Alerts ===
//         $lowStockAlerts = Product::with(['category', 'supplier'])
//             ->where('current_stock', '<=', DB::raw('reorder_level'))
//             ->orderBy('current_stock', 'asc')
//             ->limit(5)
//             ->get()
//             ->map(function ($product) {
//                 return [
//                     'id' => $product->id,
//                     'name' => $product->name,
//                     'current_stock' => $product->current_stock,
//                     'reorder_level' => $product->reorder_level,
//                     'category' => $product->category->name ?? 'N/A',
//                     'supplier' => $product->supplier->name ?? 'N/A',
//                 ];
//             });

//         // === 🔮 Forecast API Integration ===
//         $forecastValue = $this->getForecastValue();

//         // === Return Data to View ===
//         return view('dashboard', compact(
//             'totalProducts',
//             'totalSales',
//             'todaySales',
//             'lowStockProducts',
//             'monthlySalesData',
//             'dailySalesData',
//             'recentSales',
//             'topProducts',
//             'lowStockAlerts',
//             'forecastValue'
//         ));
//     }

//     /**
//      * 🔮 Send sales data to FastAPI to get forecasted sales
//      */
//     private function getForecastValue()
//     {
//         try {
//             // Get last 30 days of sales data
//             $salesData = Sale::where('created_at', '>=', now()->subDays(30))
//                 ->select('created_at as date', 'quantity')
//                 ->orderBy('created_at', 'asc')
//                 ->get()
//                 ->map(function ($sale) {
//                     return [
//                         // 'date' => $sale->date->format('Y-m-d'),
//                         'date' => Carbon::parse($sale->date)->format('Y-m-d'),

//                         'quantity' => $sale->quantity,
//                     ];
//                 });

//             // Send data to FastAPI
//             $response = Http::post('http://127.0.0.1:8001/forecast', [
//                 'sales' => $salesData,
//             ]);

//             // Parse response
            
//             $data = $response->json();
//             return $data['Forecast'] ?? 0;
//         } catch (\Exception $e) {
//             // Log error (optional)
//             \Log::error('Forecast API error: ' . $e->getMessage());
//             return 0;
//         }
//     }

//     private function getTotalSalesRevenue()
//     {
//         return DB::table('sales')
//             ->join('products', 'sales.product_id', '=', 'products.id')
//             ->sum(DB::raw('sales.quantity * products.price'));
//     }

//     private function getTodaySalesRevenue()
//     {
//         return DB::table('sales')
//             ->join('products', 'sales.product_id', '=', 'products.id')
//             ->whereDate('sales.created_at', Carbon::today())
//             ->sum(DB::raw('sales.quantity * products.price'));
//     }

//     private function getMonthlySalesData()
//     {
//         $startDate = Carbon::now()->subMonths(11)->startOfMonth();
//         $endDate = Carbon::now()->endOfMonth();

//         $salesData = DB::table('sales')
//             ->join('products', 'sales.product_id', '=', 'products.id')
//             ->select(
//                 DB::raw('DATE_FORMAT(sales.created_at, "%Y-%m") as month'),
//                 DB::raw('SUM(sales.quantity * products.price) as total_revenue'),
//                 DB::raw('SUM(sales.quantity) as total_quantity')
//             )
//             ->whereBetween('sales.created_at', [$startDate, $endDate])
//             ->groupBy('month')
//             ->orderBy('month')
//             ->get()
//             ->keyBy('month');

//         $months = [];
//         $revenues = [];
//         $quantities = [];

//         $current = $startDate->copy();
//         while ($current <= $endDate) {
//             $monthKey = $current->format('Y-m');
//             $months[] = $current->format('M Y');
//             $revenues[] = $salesData->get($monthKey)->total_revenue ?? 0;
//             $quantities[] = $salesData->get($monthKey)->total_quantity ?? 0;
//             $current->addMonth();
//         }

//         return [
//             'labels' => $months,
//             'revenues' => $revenues,
//             'quantities' => $quantities,
//         ];
//     }

//     private function getDailySalesData()
//     {
//         $startDate = Carbon::now()->subDays(29)->startOfDay();
//         $endDate = Carbon::now()->endOfDay();

//         $salesData = DB::table('sales')
//             ->join('products', 'sales.product_id', '=', 'products.id')
//             ->select(
//                 DB::raw('DATE(sales.created_at) as date'),
//                 DB::raw('SUM(sales.quantity * products.price) as total_revenue'),
//                 DB::raw('SUM(sales.quantity) as total_quantity')
//             )
//             ->whereBetween('sales.created_at', [$startDate, $endDate])
//             ->groupBy('date')
//             ->orderBy('date')
//             ->get()
//             ->keyBy('date');

//         $dates = [];
//         $revenues = [];
//         $quantities = [];

//         $current = $startDate->copy();
//         while ($current <= $endDate) {
//             $dateKey = $current->format('Y-m-d');
//             $dates[] = $current->format('M j');
//             $revenues[] = $salesData->get($dateKey)->total_revenue ?? 0;
//             $quantities[] = $salesData->get($dateKey)->total_quantity ?? 0;
//             $current->addDay();
//         }

//         return [
//             'labels' => $dates,
//             'revenues' => $revenues,
//             'quantities' => $quantities,
//         ];
//     }
// }



// namespace App\Http\Controllers;

// use App\Models\Product;
// use App\Models\Sale;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Http;
// use Carbon\Carbon;
// use Illuminate\Support\Facades\Auth;

// class DashboardController extends Controller
// {
//     public function index()
//     {
//         $user = Auth::user();

//         // === Statistics scoped by user visibility ===
//         $totalProducts = Product::visibleTo($user)->count();

//         $totalSales = $this->getTotalSalesRevenue($user);
//         $todaySales = $this->getTodaySalesRevenue($user);
//         $lowStockProducts = Product::visibleTo($user)
//             ->whereColumn('current_stock', '<=', 'reorder_level')
//             ->count();

//         // === Chart Data ===
//         $monthlySalesData = $this->getMonthlySalesData($user);
//         $dailySalesData = $this->getDailySalesData($user);

//         // === Recent Sales ===
//         $recentSales = Sale::with(['product', 'user'])
//             ->whereHas('product', function($q) use ($user) {
//                 $q->visibleTo($user);
//             })
//             ->orderBy('created_at', 'desc')
//             ->limit(5)
//             ->get()
//             ->map(function ($sale) {
//                 return [
//                     'id' => $sale->id,
//                     'product_name' => $sale->product->name,
//                     'quantity' => $sale->quantity,
//                     'unit_price' => $sale->product->price,
//                     'total_amount' => $sale->quantity * $sale->product->price,
//                     'user_name' => $sale->user->name,
//                     'created_at' => $sale->created_at->format('M d, Y H:i'),
//                 ];
//             });

//         // === Top Products ===
//         $topProducts = DB::table('sales')
//             ->join('products', 'sales.product_id', '=', 'products.id')
//             ->when(!$user->is_admin, function($query) use ($user) {
//                 $query->where(function($q) use ($user) {
//                     $q->where('products.is_global', true)
//                       ->orWhere('products.user_id', $user->id);
//                 });
//             })
//             ->select(
//                 'products.name',
//                 DB::raw('SUM(sales.quantity) as total_quantity'),
//                 DB::raw('SUM(sales.quantity * products.price) as total_revenue')
//             )
//             ->groupBy('products.id', 'products.name')
//             ->orderBy('total_revenue', 'desc')
//             ->limit(5)
//             ->get();

//         // === Low Stock Alerts ===
//         $lowStockAlerts = Product::visibleTo($user)
//             ->with(['category', 'supplier'])
//             ->whereColumn('current_stock', '<=', 'reorder_level')
//             ->orderBy('current_stock', 'asc')
//             ->limit(5)
//             ->get()
//             ->map(function ($product) {
//                 return [
//                     'id' => $product->id,
//                     'name' => $product->name,
//                     'current_stock' => $product->current_stock,
//                     'reorder_level' => $product->reorder_level,
//                     'category' => $product->category->name ?? 'N/A',
//                     'supplier' => $product->supplier->name ?? 'N/A',
//                 ];
//             });

//         // === 🔮 Forecast API Integration ===
//         $forecastValue = $this->getForecastValue($user);

//         return view('dashboard', compact(
//             'totalProducts',
//             'totalSales',
//             'todaySales',
//             'lowStockProducts',
//             'monthlySalesData',
//             'dailySalesData',
//             'recentSales',
//             'topProducts',
//             'lowStockAlerts',
//             'forecastValue'
//         ));
//     }

//     private function getForecastValue($user)
//     {
//         try {
//             $salesData = Sale::whereHas('product', function($q) use ($user) {
//                     $q->visibleTo($user);
//                 })
//                 ->where('created_at', '>=', now()->subDays(30))
//                 ->select('created_at as date', 'quantity')
//                 ->orderBy('created_at', 'asc')
//                 ->get()
//                 ->map(function ($sale) {
//                     return [
//                         'date' => Carbon::parse($sale->date)->format('Y-m-d'),
//                         'quantity' => $sale->quantity,
//                     ];
//                 });

//             $response = Http::post('http://127.0.0.1:8001/forecast', [
//                 'sales' => $salesData,
//             ]);

//             $data = $response->json();
//             return $data['Forecast'] ?? 0;

//         } catch (\Exception $e) {
//             \Log::error('Forecast API error: ' . $e->getMessage());
//             return 0;
//         }
//     }

//     private function getTotalSalesRevenue($user)
//     {
//         return DB::table('sales')
//             ->join('products', 'sales.product_id', '=', 'products.id')
//             ->when(!$user->is_admin, function($query) use ($user) {
//                 $query->where(function($q) use ($user) {
//                     $q->where('products.is_global', true)
//                       ->orWhere('products.user_id', $user->id);
//                 });
//             })
//             ->sum(DB::raw('sales.quantity * products.price'));
//     }

//     private function getTodaySalesRevenue($user)
//     {
//         return DB::table('sales')
//             ->join('products', 'sales.product_id', '=', 'products.id')
//             ->whereDate('sales.created_at', Carbon::today())
//             ->when(!$user->is_admin, function($query) use ($user) {
//                 $query->where(function($q) use ($user) {
//                     $q->where('products.is_global', true)
//                       ->orWhere('products.user_id', $user->id);
//                 });
//             })
//             ->sum(DB::raw('sales.quantity * products.price'));
//     }

//     private function getMonthlySalesData($user)
//     {
//         $startDate = Carbon::now()->subMonths(11)->startOfMonth();
//         $endDate = Carbon::now()->endOfMonth();

//         $salesData = DB::table('sales')
//             ->join('products', 'sales.product_id', '=', 'products.id')
//             ->when(!$user->is_admin, function($query) use ($user) {
//                 $query->where(function($q) use ($user) {
//                     $q->where('products.is_global', true)
//                       ->orWhere('products.user_id', $user->id);
//                 });
//             })
//             ->select(
//                 DB::raw('DATE_FORMAT(sales.created_at, "%Y-%m") as month'),
//                 DB::raw('SUM(sales.quantity * products.price) as total_revenue'),
//                 DB::raw('SUM(sales.quantity) as total_quantity')
//             )
//             ->whereBetween('sales.created_at', [$startDate, $endDate])
//             ->groupBy('month')
//             ->orderBy('month')
//             ->get()
//             ->keyBy('month');

//         $months = [];
//         $revenues = [];
//         $quantities = [];

//         $current = $startDate->copy();
//         while ($current <= $endDate) {
//             $monthKey = $current->format('Y-m');
//             $months[] = $current->format('M Y');
//             $revenues[] = $salesData->get($monthKey)->total_revenue ?? 0;
//             $quantities[] = $salesData->get($monthKey)->total_quantity ?? 0;
//             $current->addMonth();
//         }

//         return [
//             'labels' => $months,
//             'revenues' => $revenues,
//             'quantities' => $quantities,
//         ];
//     }

//     private function getDailySalesData($user)
//     {
//         $startDate = Carbon::now()->subDays(29)->startOfDay();
//         $endDate = Carbon::now()->endOfDay();

//         $salesData = DB::table('sales')
//             ->join('products', 'sales.product_id', '=', 'products.id')
//             ->when(!$user->is_admin, function($query) use ($user) {
//                 $query->where(function($q) use ($user) {
//                     $q->where('products.is_global', true)
//                       ->orWhere('products.user_id', $user->id);
//                 });
//             })
//             ->select(
//                 DB::raw('DATE(sales.created_at) as date'),
//                 DB::raw('SUM(sales.quantity * products.price) as total_revenue'),
//                 DB::raw('SUM(sales.quantity) as total_quantity')
//             )
//             ->whereBetween('sales.created_at', [$startDate, $endDate])
//             ->groupBy('date')
//             ->orderBy('date')
//             ->get()
//             ->keyBy('date');

//         $dates = [];
//         $revenues = [];
//         $quantities = [];

//         $current = $startDate->copy();
//         while ($current <= $endDate) {
//             $dateKey = $current->format('Y-m-d');
//             $dates[] = $current->format('M j');
//             $revenues[] = $salesData->get($dateKey)->total_revenue ?? 0;
//             $quantities[] = $salesData->get($dateKey)->total_quantity ?? 0;
//             $current->addDay();
//         }

//         return [
//             'labels' => $dates,
//             'revenues' => $revenues,
//             'quantities' => $quantities,
//         ];
//     }
// }


namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // === Top Metrics ===
        $totalProducts = Product::where('user_id', $user->id)->count();
        $totalSales = Sale::sum('total_amount');
        $totalPurchases = Purchase::sum(DB::raw('quantity * purchase_price'));
        $totalCustomers = Customer::count();
        $totalProfit = $totalSales - $totalPurchases;
        $todaySales = Sale::whereDate('sale_date', today())->sum('total_amount');

        // === Low Stock Count ===
        $lowStockProducts = Product::where('quantity', '<=', 10)->count();

        // === Recent Activity ===
        $recentSales = Sale::latest()->take(5)->with('customer')->get();
        $recentPurchases = Purchase::latest()->take(5)->with('supplier')->get();

        // === Charts ===
        $salesChart = $this->getSalesChartData();
        $purchaseChart = $this->getPurchaseChartData();
        $stockMovement = $this->getStockMovementData();

        // === Top 5 Products ===
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_quantity'), DB::raw('SUM(sale_items.total) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        // === Top 5 Customers ===
        $topCustomers = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->select('customers.name', DB::raw('COUNT(sales.id) as total_orders'), DB::raw('SUM(sales.total_amount) as total_spent'))
            ->groupBy('customers.id', 'customers.name')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();

        // === Top 5 Suppliers ===
        $topSuppliers = DB::table('purchases')
            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->select('suppliers.name', DB::raw('COUNT(purchases.id) as total_purchases'), DB::raw('SUM(purchases.purchase_price * purchases.quantity) as total_spent'))
            ->groupBy('suppliers.id', 'suppliers.name')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();

        // === Low Stock Alerts ===
        $lowStockAlerts = Product::with(['category', 'supplier'])
            ->where('quantity', '<=', 10)
            ->orderBy('quantity', 'asc')
            ->take(5)
            ->get();

        // === Forecast from FastAPI ===
        $forecast = $this->getForecastData();

        return view('dashboard', compact(
            'totalProducts', 'totalSales', 'totalPurchases', 'totalProfit', 'totalCustomers', 'todaySales',
            'lowStockProducts', 'recentSales', 'recentPurchases', 'salesChart', 'purchaseChart', 'stockMovement',
            'topProducts', 'topCustomers', 'topSuppliers', 'lowStockAlerts', 'forecast'
        ));
    }

    private function getSalesChartData()
    {
        $data = DB::table('sales')
            ->select(DB::raw('DATE(sale_date) as date'), DB::raw('SUM(total_amount) as total'))
            ->where('sale_date', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date'),
            'totals' => $data->pluck('total'),
        ];
    }

    private function getPurchaseChartData()
    {
        $data = DB::table('purchases')
            ->select(DB::raw('DATE(purchase_date) as date'), DB::raw('SUM(purchase_price * quantity) as total'))
            ->where('purchase_date', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date'),
            'totals' => $data->pluck('total'),
        ];
    }

    private function getStockMovementData()
    {
        return StockMovement::select('type', DB::raw('SUM(quantity) as total'))
            ->groupBy('type')
            ->pluck('total', 'type');
    }

    private function getForecastData()
    {
        try {
            $sales = Sale::select('sale_date', 'total_amount')->orderBy('sale_date')->get()->map(function ($sale) {
                return ['date' => $sale->sale_date->format('Y-m-d'), 'revenue' => $sale->total_amount];
            });

            $response = Http::post('http://127.0.0.1:8001/forecast', ['sales' => $sales]);
            return $response->json();
        } catch (\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }
}
