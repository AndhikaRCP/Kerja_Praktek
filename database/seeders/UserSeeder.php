<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'is_active' => true,
                'role' => 'admin',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'superadmin',
                'name' => 'Andre Widjaya',
                'email' => 'andreWidjaya@gmail.com',
                'password' => Hash::make('superadmin123'),
                'is_active' => true,
                'role' => 'superadmin',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'sales1',
                'name' => 'Sales_Plaju',
                'email' => 'sales1@gmail.com',
                'password' => Hash::make('sales123'),
                'is_active' => true,
                'role' => 'sales',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'sales2',
                'name' => 'Sales_Bukit',
                'email' => 'sales2@gmail.com',
                'password' => Hash::make('sales123'),
                'is_active' => true,
                'role' => 'sales',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'sales3',
                'name' => 'Sales_Kenten',
                'email' => 'sales3@gmail.com',
                'password' => Hash::make('sales123'),
                'is_active' => false,
                'role' => 'sales',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'sales4',
                'name' => 'Sales_Jakabaring',
                'email' => 'sales4@gmail.com',
                'password' => Hash::make('sales123'),
                'is_active' => true,
                'role' => 'sales',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
