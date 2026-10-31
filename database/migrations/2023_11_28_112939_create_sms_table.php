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
        Schema::create('sms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('smsgateway_id')->constrained()->cascadeOnDelete();
            $table->foreignId('senderid_id')->constrained('senderids')->cascadeOnDelete();
            $table->foreignId('group_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('number');
            $table->integer('total_sms')->nullable();
            $table->string('message_id')->nullable();
            $table->double('charge')->default(0);
            $table->string('ip_address')->nullable();
            $table->boolean('is_unicode')->default(0); // text / unicode
            $table->longText('contents');
            $table->dateTime('schedule')->nullable();
            $table->text('notes')->nullable();
            $table->string('status'); // pending / success / failed
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms');
    }
};
