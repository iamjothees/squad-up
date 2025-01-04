<?php

namespace Tests;

use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $seed = true;

    protected $seeder = RolesPermissionsSeeder::class;
}
