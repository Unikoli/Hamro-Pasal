<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $cat1 = Category::create(['name' => 'Snacks']);
        $cat2 = Category::create(['name' => 'Dairy']);

        $sup1 = Supplier::create(['name' => 'Local Distributors']);
        $sup2 = Supplier::create(['name' => 'City Imports']);

        Product::create([
            'name' => 'Wai Wai Noodles', 'category_id' => $cat1->id, 'supplier_id' => $sup1->id,
            'price' => 25.00, 'current_stock' => 100, 'reorder_level' => 20
        ]);
        Product::create([
            'name' => 'DDC Milk (1L)', 'category_id' => $cat2->id, 'supplier_id' => $sup2->id,
            'price' => 110.00, 'current_stock' => 50, 'reorder_level' => 15
        ]);
    }
}