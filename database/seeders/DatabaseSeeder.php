<?php

namespace Database\Seeders;

use App\Models\Brand\Brand;
use App\Models\CarModel\CarModel;
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
        // \App\Models\User::factory(10)->create();
        Brand::factory(20)->create();
        CarModel::factory(40)->create();
    }
}
