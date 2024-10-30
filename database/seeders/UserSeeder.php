<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => "Master",
            'email' => "master@admin.com",
            'password' => Hash::make('123456789'),
        ]);

        $adminRole = Role::where('name', 'master')->first();

        $admin->attachRole($adminRole);
    }
}
