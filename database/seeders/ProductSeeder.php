<?php
// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use App\Models\Category;
// use App\Models\Supplier;
// use App\Models\Product;

// class ProductSeeder extends Seeder
// {
//     public function run(): void
//     {
//         $cat1 = Category::create(['name' => 'Snacks']);
//         $cat2 = Category::create(['name' => 'Dairy']);

//         $sup1 = Supplier::create(['name' => 'Local Distributors']);
//         $sup2 = Supplier::create(['name' => 'City Imports']);

//         Product::create([
//             'name' => 'Wai Wai Noodles', 'category_id' => $cat1->id, 'supplier_id' => $sup1->id,
//             'price' => 25.00, 'current_stock' => 100, 'reorder_level' => 20
//         ]);
//         Product::create([
//             'name' => 'DDC Milk (1L)', 'category_id' => $cat2->id, 'supplier_id' => $sup2->id,
//             'price' => 110.00, 'current_stock' => 50, 'reorder_level' => 15
//         ]);
//     }
// }


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Make sure at least one admin exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => bcrypt('password'), 'is_admin' => true]
        );

        $cat1 = Category::create(['name' => 'Snacks']);
        $cat2 = Category::create(['name' => 'Dairy']);

        $sup1 = Supplier::create(['name' => 'Local Distributors']);
        $sup2 = Supplier::create(['name' => 'City Imports']);

        // Admin-added products (visible to all)
        Product::create([
            'name' => 'Wai Wai Noodles',
            'category_id' => $cat1->id,
            'supplier_id' => $sup1->id,
            'price' => 25.00,
            'current_stock' => 100,
            'reorder_level' => 20,
            'user_id' => $admin->id,
            'is_global' => true, // visible to all users
        ]);

        Product::create([
            'name' => 'DDC Milk (1L)',
            'category_id' => $cat2->id,
            'supplier_id' => $sup2->id,
            'price' => 110.00,
            'current_stock' => 50,
            'reorder_level' => 15,
            'user_id' => $admin->id,
            'is_global' => true, // visible to all users
        ]);
    }
}
