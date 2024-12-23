<?php

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
        Schema::create('habit_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('habit_id')->constrained();
            $table->date('entry_date');
            $table->string('value');    // the habit values like 'X', 'meh' or '9-17'   
            $table->text('note')->nullable();  // Add this for daily notes
            $table->timestamps();

            $table->index('entry_date'); // Optimize date filtering
            $table->index(['user_id', 'habit_id']); // Optimize user-habit queries
            $table->unique(['user_id', 'habit_id', 'entry_date']); 
        });   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_entries');
    }
};
