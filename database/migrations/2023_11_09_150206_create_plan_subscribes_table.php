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
        Schema::create('plan_subscribes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gateway_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_no')->nullable();
            $table->double('price')->default(0);
            $table->double('masking_rate')->default(0);
            $table->double('non_masking_rate')->default(0);
            $table->integer('total_sms')->default(0);
            $table->string('status')->default('pending'); // pending / canceled / approved
            $table->text('manual_data')->nullable();
            $table->string('attachment')->nullable();
            $table->date('will_expire');
            $table->longText('plan_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_subscribes');
    }
};
