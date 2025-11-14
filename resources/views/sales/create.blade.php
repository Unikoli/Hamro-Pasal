@extends('layouts.app')
@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold metallic-text-gold font-orbitron">RECORD NEW SALE</h2>
            <div class="flex items-center space-x-2">
                <span class="text-metallic-mid">💰</span>
                <span class="text-metallic-mid text-lg">Point of Sale</span>
            </div>
        </div>

        @if (session('success'))
            <div class="alert-success p-4 rounded-lg mb-6 flex items-center">
                <span class="text-2xl mr-3">✅</span>
                <span class="text-green-100 text-lg">{{ session('success') }}</span>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert-error p-4 rounded-lg mb-6 flex items-center">
                <span class="text-2xl mr-3">❌</span>
                <span class="text-red-100 text-lg">{{ session('error') }}</span>
            </div>
        @endif

        <div class="metallic-card p-8 rounded-xl">
            <form action="{{ route('sales.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="product_id" class="block text-metallic-mid font-bold mb-3 text-lg">Product:</label>
                    <select name="product_id" id="product_id" 
                            class="metallic-input shadow-lg border rounded-lg w-full py-4 px-4 text-lg bg-slate-800" required>
                        <option value="">-- Select a Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                {{ $product->name }} 
                                <span class="text-metallic-dark">(Stock: {{ $product->current_stock }})</span>
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') 
                        <p class="text-red-400 text-sm mt-2 flex items-center">
                            <span class="mr-2">⚠️</span>{{ $message }}
                        </p> 
                    @enderror
                </div>
                
                <div class="mb-8">
                    <label for="quantity" class="block text-metallic-mid font-bold mb-3 text-lg">Quantity Sold:</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" 
                           class="metallic-input shadow-lg appearance-none border rounded-lg w-full py-4 px-4 text-lg" 
                           min="1" placeholder="1" required>
                    @error('quantity') 
                        <p class="text-red-400 text-sm mt-2 flex items-center">
                            <span class="mr-2">⚠️</span>{{ $message }}
                        </p> 
                    @enderror
                </div>
                
                <div class="flex items-center justify-end pt-6">
                    <button type="submit" class="metallic-btn metallic-btn-success px-8 py-4 text-lg font-bold rounded-lg text-white transition-all duration-300">
                        💰 RECORD SALE
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Sale Stats -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-steel-800/50 backdrop-blur-sm p-6 rounded-xl border border-steel-700">
                <div class="text-metallic-gold text-2xl mb-2">📈</div>
                <h3 class="text-lg font-bold text-metallic-mid mb-1">Today's Sales</h3>
                <p class="text-steel-100">Track your daily performance</p>
            </div>
            
            <div class="bg-steel-800/50 backdrop-blur-sm p-6 rounded-xl border border-steel-700">
                <div class="text-metallic-gold text-2xl mb-2">⚡</div>
                <h3 class="text-lg font-bold text-metallic-mid mb-1">Quick Entry</h3>
                <p class="text-steel-100">Fast and efficient sales recording</p>
            </div>
            
            <div class="bg-steel-800/50 backdrop-blur-sm p-6 rounded-xl border border-steel-700">
                <div class="text-metallic-gold text-2xl mb-2">📊</div>
                <h3 class="text-lg font-bold text-metallic-mid mb-1">Auto Updates</h3>
                <p class="text-steel-100">Inventory updates automatically</p>
            </div>
        </div>
    </div>
@endsection
