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
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->cascadeOnDelete();

            $table->json('day_of_week')->nullable();

            // Start & End time
            $table->time('start_time');
            $table->time('end_time');

            $table->time('check_in_start_time')->nullable();

            $table->time('check_in_end_time')->nullable();

            $table->time('check_out_start_time')->nullable();

            $table->time('check_out_end_time')->nullable();

            // Optional: subject / class
            $table->string('subject')->nullable();

            // Optional: room
            $table->string('room')->nullable();

            // status
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
