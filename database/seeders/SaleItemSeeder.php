<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;

class SaleItemSeeder extends Seeder
{
    public function run(): void
    {
        $sales = Sale::all();
        $products = Product::all();

        foreach ($sales as $sale) {
            $product = $products->random();

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => rand(1, 3),
                'selling_price' => $product->selling_price,
            ]);
        }
    }
}
