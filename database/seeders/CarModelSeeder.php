<?php

namespace Database\Seeders;

use App\Models\CarModel\CarModel;
use Illuminate\Database\Seeder;

class CarModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($carModels)
    {
        foreach ($carModels as $carModel) {
            CarModel::factory(1)->create(['name' => $carModel['name'], 'brand_id' => $carModel['brand_id']]);
        }
    }
}
