<?php

namespace Database\Seeders;

use App\Models\Rental\Rental;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rental::factory(250)->create();
    }
}
