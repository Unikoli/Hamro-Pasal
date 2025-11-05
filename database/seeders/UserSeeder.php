<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@bazaar.com',
        ]);
        $admin->assignRole('admin');

        // Create Shopkeeper User
        $shopkeeper = User::factory()->create([
            'name' => 'Shopkeeper User',
            'email' => 'shop@bazaar.com',
        ]);
        $shopkeeper->assignRole('shopkeeper');
    }
}