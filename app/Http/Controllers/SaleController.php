<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
                if ($product->current_stock < $validated['quantity']) {
                    throw new \Exception("Insufficient stock! Only {$product->current_stock} available.");
                }

                Sale::create([
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                    'user_id' => auth()->id(),
                ]);

                $product->decrement('current_stock', $validated['quantity']);
            });

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
        
        // Redirect to a sales creation form or the dashboard
        return redirect()->route('dashboard')->with('success', 'Sale recorded!');
    }
}