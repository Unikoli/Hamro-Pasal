<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Get the shopkeeper user
        $user = User::where('email', 'shop@bazaar.com')->first();

        // If user doesn't exist, create one (safety check)
        if (!$user) {
            $user = User::create([
                'name' => 'Shopkeeper User',
                'email' => 'shop@bazaar.com',
                'password' => bcrypt('shopkeeper'),
                'is_admin' => false,
            ]);
        }

        // Retail shop-related categories
        $categories = [
            ['name' => 'Beverages', 'user_id' => $user->id],
            ['name' => 'Snacks', 'user_id' => $user->id],
            ['name' => 'Personal Care', 'user_id' => $user->id],
            ['name' => 'Cleaning Supplies', 'user_id' => $user->id],
            ['name' => 'Stationery', 'user_id' => $user->id],
        ];

        Category::insert($categories);
    }
}
