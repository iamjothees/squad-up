<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        
        User::create([
            'name' => 'Jotheeswaran S',
            'email' => 'iamjothees@gmail.com',
            'phone' => '+919442608662',
            'password' => Hash::make('admin@123'),
            'referal_partner_code' => 'RPC-JOE-8662',
        ])->assignRole('admin');


        User::create([
            'name' => 'Gowtham S',
            'email' => 'g83105237@gmail.com',
            'phone' => '+919361291477',
            'password' => Hash::make('admin@123'),
            'referal_partner_code' => 'RPC-GOW-1477',
        ])->assignRole('admin');

        User::create([
            'name' => 'Tamilvanan',
            'email' => 'tamil@gmail.com',
            'phone' => '+917339488948',
            'password' => Hash::make('admin@123'),
            'referal_partner_code' => 'RPC-TAM-2610',
        ])->assignRole('admin');

        User::create([
            'name' => 'Sasikumar M',
            'email' => 'sasikumar@gmail.com',
            'email' => '+919025382009',
            'password' => Hash::make('admin@123'),
            'referal_partner_code' => 'RPC-SAS-2009',
        ])->assignRole('admin');
    }
}
