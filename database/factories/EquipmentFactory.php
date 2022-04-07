<?php

namespace Database\Factories;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;


/** @var \Illuminate\Database\Eloquent\Factory $factory */


//$factory->define(Equipment::class, function (Faker $faker) {
//    return [
//        'name' => $faker->name,
//        'internal_notes' => $faker->text(),
//        'quantity'=> $faker->numberBetween(0,100)
//
//    ];
//});
//



/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'internal_notes' => $this->faker->text(),
            'quantity'=> $this->faker->randomNumber()
           ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}

