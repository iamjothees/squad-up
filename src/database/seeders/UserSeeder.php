<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.dev',
            'password' => Hash::make('admin@123'),
            'referal_partner_code' => 'RPC-ADMIN-1234',
        ])->assignRole('admin');

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@test.dev',
            'password' => Hash::make('user@123'),
            'referal_partner_code' => 'RPC-TST-1234',
        ]);
    }
}
