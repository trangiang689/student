<?php

namespace Database\Factories;

use App\Models\Classes;
use App\Models\Students;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Students::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'full_name' => $this->faker->name,
            'birth_date' => $this->faker->date(),
            'home_town' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'class_id' => Classes::all()->random()->id,
            'avatar' => '',
            'user_id' =>''
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
}
