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
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('fee_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('currency_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->decimal('amount', 15, 2);

            $table->decimal('exchange_rate', 15, 4)
                ->default(1);

            $table->decimal('base_amount', 15, 2)
                ->nullable();

            $table->decimal('discount', 15, 2)
                ->default(0);

            $table->decimal('received_amount', 15, 2)
                ->nullable();

            $table->decimal('change_amount', 15, 2)
                ->nullable();

            $table->date('paid_date')
                ->nullable();

            $table->string('payment_method')
                ->nullable();

            $table->string('receipt_no')
                ->nullable();

            $table->text('note')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_payments');
    }
};