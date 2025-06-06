<?php

namespace Database\Factories;
use Illuminate\Support\Facades\Hash;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => 'users/' . $this->faker->image('public/storage/users', 640, 480, null, false),
            'name' => $this->faker->firstName,
            'first_last_name' => $this->faker->lastName,
            'second_last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'number' => $this->faker->phoneNumber,
            'status' => $this->faker->boolean,
            'estadoEliminacion'=> false, // Fijar a false
            'password' => Hash::make('password'),
        ];
    }
    

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name.'\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }
}
