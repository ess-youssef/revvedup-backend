<?php

use App\Models\User;
use App\Models\Vehicle;
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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 15);
            $table->dateTime('date');
            $table->enum('status', ["SOLD","FORSALE"])->default("FORSALE");
            $table->timestamps();
            $table->foreignIdFor(Vehicle::class);  
            $table->foreignIdFor(User::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
