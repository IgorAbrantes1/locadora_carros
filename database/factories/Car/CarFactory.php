<?php

namespace Database\Factories\Car;

use App\Models\Car\Car;
use App\Models\CarModel\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Car::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'car_model_id' => CarModel::all()->pluck('id')[$this->faker->numberBetween(1, CarModel::all()->count() - 1)],
            'license_plate' => $this->faker->regexify('[A-Z]{3}[0-9]{1}[A-Z]{1}[0-9]{2}'),
            'available' => $this->faker->boolean,
            'km' => $this->faker->numberBetween(0, 60000)
        ];
    }
}
