<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class ProductsManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'read-products']);
        Permission::create(['name' => 'add-products']);
        Permission::create(['name' => 'edit-products']);
        Permission::create(['name' => 'delete-products']);
    }
}
