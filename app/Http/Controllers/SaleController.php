<?php
// namespace App\Http\Controllers;

// use App\Models\Product;
// use App\Models\Sale;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;

// class SaleController extends Controller
// {
    
//     public function create()
//     {
//         $products = Product::orderBy('name')->get();
//         return view('sales.create', compact('products'));
//     }

//     public function store(Request $request) {
//         $validated = $request->validate([
//             'product_id' => 'required|exists:products,id',
//             'quantity' => 'required|integer|min:1',
//         ]);

//         try {
//             DB::transaction(function () use ($validated, $request) {
//                 $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
//                 if ($product->current_stock < $validated['quantity']) {
//                     throw new \Exception("Insufficient stock! Only {$product->current_stock} available.");
//                 }

//                 Sale::create([
//                     'product_id' => $validated['product_id'],
//                     'quantity' => $validated['quantity'],
//                     'user_id' => auth()->id(),
//                 ]);

//                 $product->decrement('current_stock', $validated['quantity']);
//             });

//         } catch (\Exception $e) {
//             return back()->with('error', $e->getMessage())->withInput();
//         }
        
//         // Redirect to a sales creation form or the dashboard
//         return redirect()->route('dashboard')->with('success', 'Sale recorded!');
//     }
// }

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    // Show the sales creation form
    public function create()
    {
        // Only show products visible to the logged-in user
        $products = Product::visibleTo(Auth::user())->orderBy('name')->get();
        return view('sales.create', compact('products'));
    }

    // Store a new sale
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $product = Product::lockForUpdate()->findOrFail($validated['product_id']);

                // Ensure the logged-in user has access to this product
                if (!$product->is_global && $product->user_id !== Auth::id()) {
                    throw new \Exception("You do not have permission to sell this product.");
                }

                if ($product->current_stock < $validated['quantity']) {
                    throw new \Exception("Insufficient stock! Only {$product->current_stock} available.");
                }

                Sale::create([
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                    'user_id' => Auth::id(),
                ]);

                $product->decrement('current_stock', $validated['quantity']);
            });

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('dashboard')->with('success', 'Sale recorded!');
    }

    // Optional: List sales for the logged-in user
    public function index()
    {
        $sales = Sale::with('product')
            ->whereHas('product', function ($q) {
                $q->where('is_global', true)
                  ->orWhere('user_id', Auth::id());
            })
            ->latest()
            ->paginate(10);

        return view('sales.index', compact('sales'));
    }
}
