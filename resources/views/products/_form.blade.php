<div class="grid grid-cols-1 gap-6">
    <!-- Product Name -->
    <div>
        <label for="name" class="block text-metallic-mid font-bold mb-3 text-lg">Product Name:</label>
        <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" 
               class="metallic-input shadow-lg appearance-none border rounded-lg w-full py-4 px-4 text-lg" 
               placeholder="Enter product name..." required>
        @error('name') 
            <p class="text-red-400 text-sm mt-2 flex items-center">
                <span class="mr-2">⚠️</span>{{ $message }}
            </p> 
        @enderror
    </div>

    <!-- Category and Supplier Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="category_id" class="block text-metallic-mid font-bold mb-3 text-lg">Category:</label>
            <select name="category_id" id="category_id" 
                    class="metallic-input shadow-lg border rounded-lg w-full py-4 px-4 text-lg  bg-slate-800" required>
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') 
                <p class="text-red-400 text-sm mt-2 flex items-center">
                    <span class="mr-2">⚠️</span>{{ $message }}
                </p> 
            @enderror
        </div>

        <div>
            <label for="supplier_id" class="block text-metallic-mid font-bold mb-3 text-lg">Supplier:</label>
            <select name="supplier_id" id="supplier_id" 
                    class="metallic-input shadow-lg border rounded-lg w-full py-4 px-4 text-lg bg-slate-800" required>
                <option value="">-- Select Supplier --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @selected(old('supplier_id', $product->supplier_id ?? '') == $supplier->id)>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
            @error('supplier_id') 
                <p class="text-red-400 text-sm mt-2 flex items-center">
                    <span class="mr-2">⚠️</span>{{ $message }}
                </p> 
            @enderror
        </div>
    </div>

    <!-- purchase_price-->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label for="purchase_price" class="block text-metallic-mid font-bold mb-3 text-lg">purchase_price (Rs.):</label>
            <input type="number" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', $product->purchase_price ?? '') }}" 
                   class="metallic-input shadow-lg appearance-none border rounded-lg w-full py-4 px-4 text-lg" 
                   step="0.01" placeholder="0.00" required>
            @error('purchase_price') 
                <p class="text-red-400 text-sm mt-2 flex items-center">
                    <span class="mr-2">⚠️</span>{{ $message }}
                </p> 
            @enderror
        </div>
        <div>
            <label for="selling_price" class="block text-metallic-mid font-bold mb-3 text-lg">selling_price (Rs.):</label>
            <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price', $product->selling_price ?? '') }}" 
                   class="metallic-input shadow-lg appearance-none border rounded-lg w-full py-4 px-4 text-lg" 
                   step="0.01" placeholder="0.00" required>
            @error('selling_price') 
                <p class="text-red-400 text-sm mt-2 flex items-center">
                    <span class="mr-2">⚠️</span>{{ $message }}
                </p> 
            @enderror
        </div>
        <!-- quantity -->
        <div>
            <label for="quantity" class="block text-metallic-mid font-bold mb-3 text-lg">Quantity</label>
            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $product->quantity ?? '') }}" 
                   class="metallic-input shadow-lg appearance-none border rounded-lg w-full py-4 px-4 text-lg" 
                   placeholder="0" required>
            @error('quantity') 
                <p class="text-red-400 text-sm mt-2 flex items-center">
                    <span class="mr-2">⚠️</span>{{ $message }}
                </p> 
            @enderror
        </div>
        
        <!-- <div>
            <label for="reorder_level" class="block text-metallic-mid font-bold mb-3 text-lg">Reorder Level:</label>
            <input type="number" name="reorder_level" id="reorder_level" value="{{ old('reorder_level', $product->reorder_level ?? 10) }}" 
                   class="metallic-input shadow-lg appearance-none border rounded-lg w-full py-4 px-4 text-lg" 
                   placeholder="10" required>
            @error('reorder_level') 
                <p class="text-red-400 text-sm mt-2 flex items-center">
                    <span class="mr-2">⚠️</span>{{ $message }}
                </p> 
            @enderror
        </div> -->
    </div>
</div>

<div class="flex items-center justify-between pt-8">
    <button type="submit" class="metallic-btn metallic-btn-success px-8 py-4 text-lg font-bold rounded-lg text-white transition-all duration-300">
        💾 SAVE PRODUCT
    </button>
    <a href="{{ route('products.index') }}" class="text-metallic-mid hover:text-metallic-gold transition-colors duration-300 text-lg font-semibold">
        Cancel
    </a>
</div>
