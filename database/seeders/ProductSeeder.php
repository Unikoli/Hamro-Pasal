<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('is_admin', false)->first();
        $categories = Category::pluck('id', 'name');
        $suppliers = Supplier::pluck('id', 'name');

        $products = [
            ['name' => 'Coca Cola 500ml', 'category_id' => $categories['Beverages'], 'supplier_id' => $suppliers['Sunrise Distribution'], 'purchase_price' => 60, 'selling_price' => 80, 'quantity' => 100, 'user_id' => $user->id],
            ['name' => 'Lays Classic 50g', 'category_id' => $categories['Snacks'], 'supplier_id' => $suppliers['Nepal Snacks Supply'], 'purchase_price' => 30, 'selling_price' => 50, 'quantity' => 200, 'user_id' => $user->id],
            ['name' => 'Colgate Toothpaste 100g', 'category_id' => $categories['Personal Care'], 'supplier_id' => $suppliers['Hygiene Traders'], 'purchase_price' => 80, 'selling_price' => 120, 'quantity' => 80, 'user_id' => $user->id],
            ['name' => 'Dettol Handwash 250ml', 'category_id' => $categories['Cleaning Supplies'], 'supplier_id' => $suppliers['Everfresh Cleaning'], 'purchase_price' => 150, 'selling_price' => 200, 'quantity' => 50, 'user_id' => $user->id],
            ['name' => 'A4 Notebook', 'category_id' => $categories['Stationery'], 'supplier_id' => $suppliers['Stationery Mart'], 'purchase_price' => 70, 'selling_price' => 100, 'quantity' => 150, 'user_id' => $user->id],
        ];

        Product::insert($products);
    }
}
