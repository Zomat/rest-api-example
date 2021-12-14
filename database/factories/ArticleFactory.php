<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = new \Faker\Generator;

        return [
            'title' => $faker->sentence,
            'body' => $faker->paragraph,
        ];
    }
}
