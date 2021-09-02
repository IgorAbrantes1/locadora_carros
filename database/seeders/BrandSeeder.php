<?php

namespace Database\Seeders;

use App\Models\Brand\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($brands)
    {
        foreach ($brands as $brand) {
            Brand::factory(1)->create(['name' => $brand->Label]);
        }
    }
}
