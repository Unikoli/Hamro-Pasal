<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\User;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('is_admin', false)->first();

        $suppliers = [
            ['user_id' => $user->id, 'name' => 'Sunrise Distribution', 'company' => 'Sunrise Beverages Pvt. Ltd.', 'contact' => '9812345678', 'email' => 'info@sunrise.com', 'address' => 'New Baneshwor, Kathmandu'],
            ['user_id' => $user->id, 'name' => 'Nepal Snacks Supply', 'company' => 'Nepal Snacks Co.', 'contact' => '9807654321', 'email' => 'order@nepalsnacks.com', 'address' => 'Birgunj'],
            ['user_id' => $user->id, 'name' => 'Hygiene Traders', 'company' => 'Hygiene Care Distributors', 'contact' => '9823412345', 'email' => 'sales@hygiene.com', 'address' => 'Patan'],
            ['user_id' => $user->id, 'name' => 'Everfresh Cleaning', 'company' => 'Everfresh Cleaning Goods', 'contact' => '9845123456', 'email' => 'info@everfresh.com', 'address' => 'Thimi, Bhaktapur'],
            ['user_id' => $user->id, 'name' => 'Stationery Mart', 'company' => 'Office Needs Nepal', 'contact' => '9801122334', 'email' => 'sales@stationerymart.com', 'address' => 'Kalanki, Kathmandu'],
        ];

        Supplier::insert($suppliers);
    }
}
