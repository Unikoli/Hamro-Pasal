<?php
// namespace App\Http\Controllers;

// use App\Models\Product;
// use App\Models\Category;
// use App\Models\Supplier;
// use Illuminate\Http\Request;

// class ProductController extends Controller
// {
//     public function index()
//     {
//         $products = Product::with(['category', 'supplier'])->latest()->paginate(10);
//         return view('products.index', compact('products'));
//     }

//     public function create()
//     {
//         $categories = Category::all();
//         $suppliers = Supplier::all();
//         return view('products.create', compact('categories', 'suppliers'));
//     }

//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'name' => 'required|string|max:255',
//             'category_id' => 'required|exists:categories,id',
//             'supplier_id' => 'required|exists:suppliers,id',
//             'price' => 'required|numeric|min:0',
//             'current_stock' => 'required|integer|min:0',
//             'reorder_level' => 'required|integer|min:0',
//         ]);

//         Product::create($validated);
//         return redirect()->route('products.index')->with('success', 'Product created successfully.');
//     }

//     public function edit(Product $product)
//     {
//         $categories = Category::all();
//         $suppliers = Supplier::all();
//         return view('products.edit', compact('product', 'categories', 'suppliers'));
//     }

//     public function update(Request $request, Product $product)
//     {
//         $validated = $request->validate([
//             'name' => 'required|string|max:255',
//             'category_id' => 'required|exists:categories,id',
//             'supplier_id' => 'required|exists:suppliers,id',
//             'price' => 'required|numeric|min:0',
//             'current_stock' => 'required|integer|min:0',
//             'reorder_level' => 'required|integer|min:0',
//         ]);

//         $product->update($validated);
//         return redirect()->route('products.index')->with('success', 'Product updated successfully.');
//     }

//     public function destroy(Product $product)
//     {
//         $product->delete();
//         return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
//     }
// }


namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        //   public function index() { $products = Product::with('supplier')->get(); return view('products.index', compact('products')); }
        // Show products visible to the logged-in user
        $products = Product::with(['category', 'supplier'])
            ->visibleTo(Auth::user())
            ->latest()
            ->paginate(10);

        return view('products.index', compact('products'));
        // $products = Product::with('supplier')->get();
        //  return view('products.index', compact('products'));
    }


    public function create()
{
    // Auth::user();
    $categories = Category::all();
    $suppliers = Supplier::all();

    // You can also pass isAdmin to the view if needed
    // $isAdmin = auth()->user()->is_admin;

    return view('products.create', compact('categories', 'suppliers'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'required|exists:suppliers,id',
        'quantity' => 'required|numeric|min:0',
        'purchase_price' => 'required|numeric|min:0',
        'selling_price' => 'required|numeric|min:0',
    ]);

    // $user = auth()->user();
    // $user = Auth::user();
    $validated['user_id'] = Auth::id();

    Product::create($validated);

    return redirect()->route('products.index')->with('success', 'Product created successfully.');
}

    public function edit(Product $product)
    {
        // $this->authorize('update', $product); // Optional: use policy to restrict editing

        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        // $this->authorize('update', $product); // Optional: use policy

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        // Only admins can change global status
        // if (!Auth::user()->is_admin) {
        //     unset($validated['is_global']);
        // }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
            
    }

    // public function destroy(Product $product)
    // {
    //     // $this->authorize('delete', $product); // Optional: use policy

    //     $product->delete();

    //     return redirect()->route('products.index')
    //         ->with('success', 'Product deleted successfully.');
    // }
    public function destroy(Product $product)
{
    // 🔒 If the product is global and the current user is not an admin, block deletion
    if ($product->is_global && !auth()->user()->hasRole('admin')) {
        return redirect()->route('products.index')
            ->with('error', 'You are not allowed to delete global products.');
    }

    // ✅ Otherwise, allow deletion
    $product->delete();

    return redirect()->route('products.index')
        ->with('success', 'Product deleted successfully.');
}

}
