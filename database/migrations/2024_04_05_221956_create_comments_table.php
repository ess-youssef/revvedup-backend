<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->timestamps();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Post::class);
            $table->foreignIdFor(Comment::class, "parent_comment_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
