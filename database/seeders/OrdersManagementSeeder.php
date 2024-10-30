<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class OrdersManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'read-orders']);
        Permission::create(['name' => 'add-orders']);
        Permission::create(['name' => 'edit-orders']);
        Permission::create(['name' => 'delete-orders']);
    }
}
