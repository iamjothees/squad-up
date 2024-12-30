<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([ 'name' => 'Guidance for College projects' ]);
        Service::create([ 'name' => 'Web Design' ]);
        Service::create([ 'name' => 'Web Development' ]);
        Service::create([ 'name' => 'Software Development' ]);
        Service::create([ 'name' => 'Mobile App Development' ]);
        // Service::create([ 'name' => 'Logo Design' ]);
        // Service::create([ 'name' => 'Graphic Design' ]);
        // Service::create([ 'name' => 'Digital Marketing' ]);
    }
}
