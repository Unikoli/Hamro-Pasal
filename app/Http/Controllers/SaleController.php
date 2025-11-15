<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\StockMovement;
// use Illuminate\Container\Facade\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Barryvdh\DomPDF\Facade\Pdf;
use PDF; // add at the top

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('customer')->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('sales.create', compact('customers', 'products'));
    }

    // public function store(Request $request)

    // {
    //     $validated = $request->validate([
    //         'customer_id' => 'nullable|exists:customers,id',
    //         'products.*.product_id' => 'required|exists:products,id',
    //         'products.*.quantity' => 'required|integer|min:1',
    //         'products.*.selling_price' => 'required|numeric|min:0',
    //         'discount' => 'nullable|numeric|min:0',
    //         'tax' => 'nullable|numeric|min:0',
    //         'payment_method' => 'nullable|string|max:50',
    //         'sale_date' => 'required|date',
    //     ]);

    //     DB::transaction(function () use ($validated) {
    //         $totalAmount = collect($validated['products'])->sum(fn($p) => $p['selling_price'] * $p['quantity']);

    //         $sale = Sale::create([
    //             'customer_id' => $validated['customer_id'] ?? null,
    //             'total_amount' => $totalAmount,
    //             'discount' => $validated['discount'] ?? 0,
    //             'tax' => $validated['tax'] ?? 0,
    //             'payment_method' => $validated['payment_method'] ?? 'Cash',
    //             'sale_date' => $validated['sale_date'],
    //         ]);

    //         foreach ($validated['products'] as $productData) {
    //             SaleItem::create([
    //                 'sale_id' => $sale->id,
    //                 'product_id' => $productData['product_id'],
    //                 'quantity' => $productData['quantity'],
    //                 'selling_price' => $productData['selling_price'],
    //                 'total' => $productData['quantity'] * $productData['selling_price'],
    //             ]);

    //             // Update stock
    //             $product = Product::find($productData['product_id']);
    //             if ($product->quantity < $productData['quantity']) {
    //                 throw new \Exception("Insufficient stock for {$product->name}");
    //             }
    //             $product->decrement('quantity', $productData['quantity']);

    //             // 🔥 Record stock movement (OUT)
    //             StockMovement::create([
    //                 'product_id' => $product->id,
    //                 'type' => 'OUT',
    //                 'quantity' => $productData['quantity'],
    //                 'description' => "Sold via Sale ID {$sale->id}",
    //                 'created_by' => Auth::id(),
    //             ]);
    //         }
    //     });

    //     return redirect()->route('sales.index')->with('success', 'Sale recorded successfully!');
    // }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.selling_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'sale_date' => 'required|date',
        ]);

        DB::transaction(function () use ($validated) {

            // Find or create customer
            $customer = null;
            if (!empty($validated['customer_name'])) {
                $customer = Customer::firstOrCreate(
                    ['name' => $validated['customer_name']]
                );
            }

            // Calculate total amount
            $totalAmount = collect($validated['products'])->sum(fn($p) => $p['selling_price'] * $p['quantity']);

            // Create sale
            $sale = Sale::create([
                'customer_id' => $customer->id ?? null,
                'total_amount' => $totalAmount,
                'discount' => $validated['discount'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'payment_method' => $validated['payment_method'] ?? 'Cash',
                'sale_date' => $validated['sale_date'],
            ]);

            // Process products
            foreach ($validated['products'] as $productData) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'selling_price' => $productData['selling_price'],
                    'total' => $productData['quantity'] * $productData['selling_price'],
                ]);

                $product = Product::find($productData['product_id']);
                if ($product->quantity < $productData['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }
                $product->decrement('quantity', $productData['quantity']);

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'OUT',
                    'quantity' => $productData['quantity'],
                    'description' => "Sold via Sale ID {$sale->id}",
                    'created_by' => Auth::id(),
                ]);
            }
        });

        return redirect()->route('sales.index')->with('success', 'Sale recorded successfully!');
    }

    /**
     * Download sales report as PDF
     */
    public function downloadReport(Request $request)
    {
        // Validate input for custom date range
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date'   => 'nullable|date|after_or_equal:from_date',
            'type'      => 'nullable|string|in:daily,monthly,yearly,all',
        ]);

        $query = Sale::with('customer', 'saleItems.product');

        // Filter by type
        if ($request->type === 'daily') {
            $query->whereDate('sale_date', today());
        } elseif ($request->type === 'monthly') {
            $query->whereMonth('sale_date', now()->month)
                ->whereYear('sale_date', now()->year);
        } elseif ($request->type === 'yearly') {
            $query->whereYear('sale_date', now()->year);
        }

        // Custom date range
        if ($request->from_date && $request->to_date) {
            $query->whereBetween('sale_date', [$request->from_date, $request->to_date]);
        }

        $sales = $query->orderBy('sale_date', 'desc')->get();

        $pdf = PDF::loadView('sales.report', compact('sales'))
            ->setPaper('a4', 'landscape');

        $filename = 'sales_report_' . now()->format('Y_m_d_H_i') . '.pdf';

        return $pdf->download($filename);
    }
}
