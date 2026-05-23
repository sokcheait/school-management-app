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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name_kh')->nullable();
            $table->string('name_en')->nullable();
            $table->string('code')->nullable();
            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_no')->unique()->nullable();
            $table->string('name_kh')->nullable();
            $table->string('name_en')->nullable();
            $table->string('gender')->nullable();

            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();

            $table->date('date_of_birth')->nullable();
            $table->decimal('base_salary', 12, 2)->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('teachers');
    }
};
