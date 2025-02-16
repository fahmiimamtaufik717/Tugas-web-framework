<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

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
    return [
      'name' => fake()->name(),
      'email' => fake()->unique()->safeEmail(),
      'email_verified_at' => now(),
      'password' => static::$password ??= Hash::make('password'),
      'role_id' => Role::inRandomOrder()->first()->id,  // Get a random role_id from the roles table
    ];
  }

  /**
   * Indicate that the model's email address should be unverified.
   */
  public function unverified(): static
  {
    return $this->state(fn(array $attributes) => [
      'email_verified_at' => null,
    ]);
  }

  /**
   * Create a User with an associated Profile.
   *
   * @return Factory
   */
  public function withProfile(): static
  {
    return $this->afterCreating(function (User $user) {
      // Create a Profile for the user without using ProfileFactory
      $user->profile()->create([
        'biodata' => fake()->paragraph(),
        'age' => fake()->numberBetween(18, 80),
        'address' => fake()->address(),
        'avatar' => fake()->imageUrl(),
      ]);
    });
  }
}
