<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Applicant;

public function definition()
{
    return [
        'name' => $this->faker->name,
        'email' => $this->faker->unique()->safeEmail,
        'status' => 'unprocess'
    ];
}
