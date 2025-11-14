<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('is_admin', false)->first();
        $products = Product::all();

        foreach (range(1, 5) as $i) {
            $product = $products->random();
            $type = rand(0, 1) ? 'IN' : 'OUT';
            $qty = rand(5, 20);

            StockMovement::create([
                'product_id' => $product->id,
                'type' => $type,
                'quantity' => $qty,
                'description' => $type === 'IN' ? 'Received new stock' : 'Adjusted for sale',
                'created_by' => $user->id,
                'created_at' => Carbon::now()->subDays(rand(1, 10)),
            ]);
        }
    }
}
