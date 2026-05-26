<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => $this->faker->unique()->randomElement([
                'media/example_1.png',
                'media/example_2.png',
                'media/example_3.png',
                'media/example_4.png',
                'media/example_5.png',
                'media/example_6.png',
                'media/example_7.png',
                'media/example_8.png',
                'media/example_9.png',
                'media/example_10.png',
                'media/example_11.png',
                'media/example_12.png',
            ])
        ];
    }
}
