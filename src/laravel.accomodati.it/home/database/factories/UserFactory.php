<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        //
        // https://fakerphp.github.io/formatters/
        // https://fakerphp.github.io/locales/it_IT/
        //
        $first_name = $this->faker->firstName();
        $last_name = $this->faker->lastName();
        //$email = $this->faker->unique()->safeEmail();
        $email = preg_replace('/[^A-Za-z0-9\-\.]/', '', Str::lower(Str::ascii($first_name . '.' . $last_name))) . '@accomodati.it';

        return [
            'name' => $first_name . ' ' . $last_name,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'city' => $this->faker->state(),
            'country' => 'Italia',
            'address' => $this->faker->streetAddress(),
            'postcode' => $this->faker->postcode(),
            'company' => $this->faker->company(),
            'vat' => $this->faker->vat(),
            'email' => $email,
            'email_verified_at' => now(),
            'password' => \Hash::make('demopassword'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     *
     * @return $this
     */
    public function withPersonalTeam()
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(function (array $attributes, User $user) {
                    return ['name' => $user->name.'\'s Team', 'user_id' => $user->id, 'personal_team' => true];
                }),
            'ownedTeams'
        );
    }
}
