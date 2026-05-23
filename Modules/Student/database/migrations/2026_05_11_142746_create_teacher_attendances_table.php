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
        Schema::create('teacher_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('attendance_date');

            $table->enum('status', [
                'present',
                'absent',
                'late',
                'leave',
                'early_leave'
            ])->default('present');

            $table->time('check_in')->nullable();
            $table->string('check_in_status')->nullable();
            $table->time('check_out')->nullable();
            $table->string('check_out_status')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();

            $table->unique([
                'teacher_id',
                'attendance_date'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_attendances');
    }
};
