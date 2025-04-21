<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstNames = [
            'علی', 'محمد', 'رضا', 'حسین', 'مهدی', 'امیر', 'سعید', 'حسن', 'احمد', 'محسن',
        ];

        $lastNames = [
            'محمدی', 'احمدی', 'حسینی', 'رضایی', 'کریمی', 'موسوی', 'جعفری', 'صادقی', 'نجفی', 'علوی',
        ];

        $firstName = $this->faker->randomElement($firstNames);
        $lastName = $this->faker->randomElement($lastNames);


        return [
            'name' => $firstName,
            'family' => $lastName,
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'birthday' => $this->faker->dateTimeBetween('-40 years', '-20 years'),
            'gender' => 'male',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }


    public function employee(): static
    {
        return $this->afterCreating(function ($user) {
            $user->assignRole('employee');
        });
    }

    public function admin(): static
    {
        return $this->afterCreating(function ($user) {
            $user->assignRole('admin');
        });
    }
}
