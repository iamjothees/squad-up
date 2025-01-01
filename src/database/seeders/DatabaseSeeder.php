<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesPermissionsSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(AdminSeeder::class);
        
    }
}
