<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Super Admin User
        $superAdmin = User::create([
            'name' => 'Akhlaque Ahmad',
            'email' => 'i4akhlaque@gmail.com',
            'password' => Hash::make('password')
        ]);
        $superAdmin->assignRole('Super Admin');

        // Creating Admin User
        $admin = User::create([
            'name' => 'Aatir',
            'email' => 'aatir@gmail.com',
            'password' => Hash::make('password')
        ]);
        $admin->assignRole('Admin');

        // Creating Product Manager User
        $productManager = User::create([
            'name' => 'Abdul Sakoor',
            'email' => 'abdul@gmail.com',
            'password' => Hash::make('password')
        ]);
        $productManager->assignRole('Product Manager');
    }
}