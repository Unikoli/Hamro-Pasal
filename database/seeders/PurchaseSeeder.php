<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('is_admin', false)->first();
        $products = Product::all();

        foreach ($products as $product) {
            Purchase::create([
                'supplier_id' => $product->supplier_id,
                'product_id' => $product->id,
                'quantity' => rand(10, 50),
                'purchase_price' => $product->purchase_price,
                'purchase_date' => Carbon::now()->subDays(rand(1, 30)),
                'user_id' => $user->id,
            ]);
        }
    }
}
