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
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('province_code')->unique();
            $table->string('province_kh');
            $table->string('province_en');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('province_code');
            $table->string('district_code')->unique();
            $table->string('district_en');
            $table->string('district_kh');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('province_code');
        });

        Schema::create('communes', function (Blueprint $table) {
            $table->id();
            $table->string('province_code');
            $table->string('district_code');
            $table->string('commune_code')->unique();
            $table->string('commune_kh');
            $table->string('commune_en');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes for faster lookups
            $table->index('province_code');
            $table->index('district_code');
        });

        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->string('province_code');
            $table->string('district_code');
            $table->string('commune_code');
            $table->string('village_code')->unique();
            $table->string('village_kh');
            $table->string('village_en');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('communes');
        Schema::dropIfExists('villages');
    }
};
