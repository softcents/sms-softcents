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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->double('price')->default(0);
            $table->boolean('allow_api')->default(1);
            $table->double('masking_rate')->default(0);
            $table->double('non_masking_rate')->default(0);
            $table->integer('total_sms')->default(0);
            $table->string('duration'); // weekly, monthly, 3_monthly, 6_monthly, yearly
            $table->longText('features')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
