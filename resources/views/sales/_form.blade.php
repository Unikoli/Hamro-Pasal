@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h2 class="text-4xl font-bold metallic-text-gold font-orbitron mb-6">CREATE SALE</h2>

    <form action="{{ route('sales.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-6">
            <!-- Customer -->
            <!-- Customer -->
            <div>
                <label for="customer_name" class="block text-metallic-mid font-bold mb-3 text-lg">Customer:</label>
                <input list="customers-list" name="customer_name" id="customer_name"
                    value="{{ old('customer_name') }}"
                    class="metallic-input shadow-lg border rounded-lg w-full py-4 px-4 text-lg bg-slate-800"
                    placeholder="Select or type customer name">

                <datalist id="customers-list">
                    @foreach($customers as $customer)
                    <option value="{{ $customer->name }}"></option>
                    @endforeach
                </datalist>

                @error('customer_name')
                <p class="text-red-400 text-sm mt-2 flex items-center">
                    <span class="mr-2">⚠️</span>{{ $message }}
                </p>
                @enderror
            </div>


            <!-- Sale Date -->
            <div>
                <label for="sale_date" class="block text-metallic-mid font-bold mb-3 text-lg">Sale Date:</label>
                <input type="date" name="sale_date" id="sale_date" value="{{ old('sale_date', now()->format('Y-m-d')) }}"
                    class="metallic-input shadow-lg border rounded-lg w-full py-4 px-4 text-lg" required>
                @error('sale_date')
                <p class="text-red-400 text-sm mt-2 flex items-center">
                    <span class="mr-2">⚠️</span>{{ $message }}
                </p>
                @enderror
            </div>

            <!-- Products Table -->
            <div class="metallic-card p-6 rounded-xl shadow-lg">
                <h3 class="text-xl font-bold text-metallic-mid mb-4">Products</h3>

                <div id="products-wrapper" class="grid gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 product-row">
                        <div>
                            <label class="block text-metallic-mid font-semibold mb-1">Product:</label>
                            <select name="products[0][product_id]"
                                class="metallic-input shadow-lg border rounded-lg w-full py-3 px-3 text-lg bg-slate-800">
                                <option value="">-- Select Product --</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-metallic-mid font-semibold mb-1">Quantity:</label>
                            <input type="number" name="products[0][quantity]"
                                class="metallic-input shadow-lg border rounded-lg w-full py-3 px-3 text-lg" min="1" value="1">
                        </div>

                        <div>
                            <label class="block text-metallic-mid font-semibold mb-1">Price (Rs.):</label>
                            <input type="number" name="products[0][selling_price]"
                                class="metallic-input shadow-lg border rounded-lg w-full py-3 px-3 text-lg" step="0.01" value="0.00">
                        </div>

                        <div class="flex items-end space-x-2">
                            <button type="button" class="add-product metallic-btn metallic-btn-success px-4 py-2 rounded-lg text-white font-bold">
                                + Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discount & Tax -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="discount" class="block text-metallic-mid font-bold mb-3 text-lg">Discount (Rs.):</label>
                    <input type="number" name="discount" id="discount" value="{{ old('discount', 0) }}"
                        class="metallic-input shadow-lg border rounded-lg w-full py-4 px-4 text-lg" step="0.01">
                    @error('discount')
                    <p class="text-red-400 text-sm mt-2 flex items-center">
                        <span class="mr-2">⚠️</span>{{ $message }}
                    </p>
                    @enderror
                </div>

                <div>
                    <label for="tax" class="block text-metallic-mid font-bold mb-3 text-lg">Tax (Rs.):</label>
                    <input type="number" name="tax" id="tax" value="{{ old('tax', 0) }}"
                        class="metallic-input shadow-lg border rounded-lg w-full py-4 px-4 text-lg" step="0.01">
                    @error('tax')
                    <p class="text-red-400 text-sm mt-2 flex items-center">
                        <span class="mr-2">⚠️</span>{{ $message }}
                    </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-between pt-8">
            <button type="submit" class="metallic-btn metallic-btn-success px-8 py-4 text-lg font-bold rounded-lg text-white transition-all duration-300">
                💾 RECORD SALE
            </button>
            <a href="{{ route('sales.index') }}" class="text-metallic-mid hover:text-metallic-gold transition-colors duration-300 text-lg font-semibold">
                Cancel
            </a>
        </div>
    </form>
</div>

{{-- Optional JS to clone product rows --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let index = 1;
        document.querySelectorAll('.add-product').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('.product-row');
                const clone = row.cloneNode(true);
                clone.querySelectorAll('select, input').forEach(el => {
                    el.name = el.name.replace(/\d+/, index);
                    if (el.tagName === 'INPUT') el.value = el.type === 'number' ? '0' : '';
                    else el.selectedIndex = 0;
                });
                row.parentNode.appendChild(clone);
                index++;
            });
        });
    });
</script>
@endsection