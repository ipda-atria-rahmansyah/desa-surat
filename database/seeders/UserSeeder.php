<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'username' => 'admin01',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('admin1234'),
        ]);

        User::create([
            'name' => 'operator',
            'username' => 'operator01',
            'email' => 'operator@gmail.com',
            'role' => 'operator',
            'password' => Hash::make('operator1234'),
        ]);
    }
}
