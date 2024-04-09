<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\CommentUpvote;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\Listing;
use App\Models\Post;
use App\Models\PostUpvote;
use App\Models\User;
use App\Models\Vehicle;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(20)->create();
        Vehicle::factory(20)->create();
        Listing::factory(20)->create();
        Post::factory(20)->create();
        PostUpvote::factory(20)->create();
        Comment::factory(20)->create();
        CommentUpvote::factory(20)->create();
        Event::factory(20)->create();
        EventAttendance::factory(20)->create();
        
    }
}
