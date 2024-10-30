<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Role::create([
            'name' => 'admin',
        ]);

        \App\Models\Role::create([
            'name' => 'employee',
        ]);

        \App\Models\Role::create([
            'name' => 'master',
        ]);
        \App\Models\Role::create([
            'name' => 'gerant product',
        ]);
        \App\Models\Role::create([
            'name' => 'gerant Validation BL',
        ]);
        \App\Models\Role::create([
            'name' => 'gerant Rapport',
        ]);
    }
}
