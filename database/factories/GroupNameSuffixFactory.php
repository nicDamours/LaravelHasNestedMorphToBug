<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupNameSuffixFactory extends Factory
{
    public function definition()
    {
        return [
            'group_id' => Group::factory(),
            'suffix' => $this->faker->title()
        ];
    }
}