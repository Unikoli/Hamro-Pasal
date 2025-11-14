<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Customer;
use Carbon\Carbon;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::pluck('id')->toArray();

        foreach (range(1, 5) as $i) {
            Sale::create([
                'customer_id' => $customers[array_rand($customers)],
                'total_amount' => rand(500, 3000),
                'discount' => rand(0, 100),
                'tax' => rand(0, 150),
                'payment_method' => 'Cash',
                'sale_date' => Carbon::now()->subDays(rand(1, 10)),
            ]);
        }
    }
}
