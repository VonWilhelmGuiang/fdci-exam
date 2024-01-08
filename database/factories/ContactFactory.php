<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Account;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => fake()->randomElement(Account::pluck('account_id')),
            'name' => fake()->name(),
            'company' => fake()->company(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'active' => 1
        ];
    }
}
