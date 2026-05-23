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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('month');

            $table->integer('year');

            $table->decimal('base_salary', 12, 2)->default(0);

            $table->decimal('allowance', 12, 2)->default(0);

            $table->decimal('deduction', 12, 2)->default(0);

            $table->decimal('net_salary', 12, 2)->default(0);

            $table->string('status')->default('pending');

            $table->timestamp('paid_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
