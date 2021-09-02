<?php

namespace Database\Factories\Customer;

use App\Models\Customer\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'cpf' => $this->faker->unique()->cpf(false),
            'rg' => $this->faker->unique()->rg(false),
            'ddd' => $this->faker->areaCode(),
            'phone' => $this->faker->phone(false),
            'country' => $this->faker->country,
            'state' => $this->faker->stateAbbr(),
            'city' => $this->faker->city,
            'street' => $this->faker->streetName,
            'number' => $this->faker->buildingNumber,
            'postal_code' => $this->faker->postcode,
        ];
    }
}
