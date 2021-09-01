<?php

namespace Database\Factories\Brand;

use App\Models\Brand\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Brand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {

        return [
            'name' => $this->faker->unique()->lastName,
            'image' => $this->faker->image('public/storage/brands/images', 640, 480, null, false)
        ];
    }
}
