<?php
namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use App\Models\User;

// class UserSeeder extends Seeder
// {
//     public function run(): void
//     {
//         // Create Admin User
//         $admin = User::factory()->create([
//             'name' => 'Admin User',
//             'email' => 'admin@bazaar.com',
//             'password' => bcrypt('admin')
//         ]);
//         $admin->assignRole('admin');

//         // Create Shopkeeper Users
//         $shopkeeper = User::factory()->create([
//             'name' => 'Shopkeeper User',
//             'email' => 'shop@bazaar.com',
//             'password' => bcrypt('shopkeeper')
//         ]);
//         $shopkeeper->assignRole('shopkeeper');
//     }
// }

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@bazaar.com',
            'password' => bcrypt('admin'),
            'is_admin' => true,
        ]);

        // Regular user
        User::create([
            'name' => 'Shopkeeper User',
            'email' => 'shop@bazaar.com',
            'password' => bcrypt('shopkeeper'),
            'is_admin' => false,
        ]);
    }
}
