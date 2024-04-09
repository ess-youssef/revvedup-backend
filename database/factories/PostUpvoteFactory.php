<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostUpvote>
 */
class PostUpvoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id');
        $postIds = Post::pluck('id');
        return [
            'user_id' => fake()->randomElement($userIds),
            'post_id' => fake()->randomElement($postIds),
        ];
    }
}
