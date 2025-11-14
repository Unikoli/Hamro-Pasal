<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Walk-in Customer', 'contact' => 'N/A', 'email' => null],
            ['name' => 'Ram Thapa', 'contact' => '9801000001', 'email' => 'ram@example.com'],
            ['name' => 'Sita Gurung', 'contact' => '9801000002', 'email' => 'sita@example.com'],
            ['name' => 'Hari Basnet', 'contact' => '9801000003', 'email' => 'hari@example.com'],
            ['name' => 'Gita KC', 'contact' => '9801000004', 'email' => 'gita@example.com'],
        ];

        Customer::insert($customers);
    }
}
