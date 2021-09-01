<?php

namespace Database\Factories\CarModel;

use App\Models\Brand\Brand;
use App\Models\CarModel\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CarModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'brand_id' => $this->faker->numberBetween(1, Brand::all()->count()),
            'name' => $this->faker->unique->firstName,
            'image' => $this->faker->image('public/storage/carModels/images', 640, 480, null, false),
            'num_doors' => $this->faker->randomElement([1, 2, 4, 6]),
            'num_seats' => $this->faker->randomElement([1, 2, 5, 8]),
            'air_bag' => $this->faker->boolean,
            'abs' => $this->faker->boolean,
        ];
    }
}
