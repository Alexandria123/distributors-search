<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Distributor;
use App\Models\Region;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Region::factory()->has(City::factory()->count(3)->has(Distributor::factory()->count(4)))->create();
    }
}
