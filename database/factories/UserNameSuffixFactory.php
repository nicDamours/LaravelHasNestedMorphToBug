<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserNameSuffixFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'suffix' => $this->faker->title()
        ];
    }
}