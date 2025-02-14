<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();  // Regular auto-incrementing ID instead of UUID
            $table->string('type');
            $table->unsignedBigInteger('user_id');  // Instead of morphs
            $table->text('data');
            $table->string('url')->nullable();  // Added URL field
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};