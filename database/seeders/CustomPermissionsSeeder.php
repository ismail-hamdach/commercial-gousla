<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProductsManagementSeeder::class, 
            CompanyManagementSeeder::class, 
            UserManagementSeeder::class, 
            ClientManagementSeeder::class, 
            OrdersManagementSeeder::class
        ]);
    }
}
