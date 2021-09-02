<?php

namespace Database\Seeders;

use App\Models\Car\Car;
use App\Models\CarModel\CarModel;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Car::factory(CarModel::all()->count())->create();
    }
}
