<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommentUpvote>
 */
class CommentUpvoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id');
        $commentIds = Comment::pluck('id');
        return [
            'user_id' => fake()->randomElement($userIds),
            'comment_id' => fake()->randomElement($commentIds),
        ];
    }
}
