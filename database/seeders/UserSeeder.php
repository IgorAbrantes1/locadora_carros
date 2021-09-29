<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(1)->create([
            'name' => 'Igor Abrantes',
            'email' => 'igor@email.com',
            'email_verified_at' => now(),
            'password' => env('DEFAULT_PASSWORD', 'secret'),
            'remember_token' => Str::random(10),
        ]);
    }
}
