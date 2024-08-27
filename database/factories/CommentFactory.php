<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'post_id' => rand(1, User::count()),
            'content' => fake()->paragraph(),
            'user_id' => rand(1, User::count()),
            'image' => "default_picture" . rand(1,5) . ".jpg",
            'tags' => fake()->words(3, true),
        ];
    }
}
