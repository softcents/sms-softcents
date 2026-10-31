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
        Schema::create('campaign_sms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('senderid_id')->constrained('senderids')->cascadeOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained()->cascadeOnDelete();
            $table->longText('numbers');
            $table->double('charges')->default(0);
            $table->integer('total_sms')->nullable();
            $table->string('ip_address')->nullable();
            $table->boolean('is_unicode')->default(0); // text / unicode
            $table->longText('contents');
            $table->dateTime('schedule')->nullable();
            $table->text('notes')->nullable();
            $table->string('status'); // pending / success / failed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_sms');
    }
};
