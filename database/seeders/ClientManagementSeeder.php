<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class ClientManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'read-clients']);
        Permission::create(['name' => 'add-clients']);
        Permission::create(['name' => 'edit-clients']);
        Permission::create(['name' => 'delete-clients']);
    }
}
