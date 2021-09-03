<?php

namespace Database\Factories\Rental;

use App\Models\Car\Car;
use App\Models\Customer\Customer;
use App\Models\Rental\Rental;
use Illuminate\Database\Eloquent\Factories\Factory;

class RentalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rental::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $start_date_period = $this->faker->dateTimeBetween('-1 years', '-1 week');
        $end_date_expected_period = $this->faker->dateTimeBetween($start_date_period, '+2 months');
        $end_date_performed_period = $this->faker->dateTimeBetween($start_date_period, '+4 months');
        $km_initial = $this->faker->numberBetween(0, 100000);
        $km_final = $this->faker->numberBetween($km_initial, $km_initial + 5000);

        return [
            'customer_id' => Customer::all()->pluck('id')[$this->faker->numberBetween(1, Customer::all()->count() - 1)],
            'car_id' => Car::all()->pluck('id')[$this->faker->numberBetween(1, Car::all()->count() - 1)],
            'start_date_period' => $start_date_period,
            'end_date_expected_period' => $end_date_expected_period,
            'end_date_performed_period' => $end_date_performed_period,
            'daily_value' => $this->faker->randomFloat(8, 0.5, 3000),
            'km_initial' => $km_initial,
            'km_final' => $km_final,
        ];
    }
}
