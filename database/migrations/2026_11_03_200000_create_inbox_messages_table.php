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
        if (!Schema::hasTable('inbox_messages')) {
            Schema::create('inbox_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('gateway_id')->nullable()->index();
                $table->string('gateway_type', 50)->nullable()->default('anbernet')->index();
                $table->string('sender', 30)->index(); // Phone of customer/user who sent SMS (e.g. 8801713252758)
                $table->string('receiver', 30)->index(); // Assigned Virtual Number / Sender ID (e.g. 8809639189627)
                $table->text('message');
                $table->string('message_id', 100)->nullable()->index(); // ID from remote gateway
                $table->string('sms_status', 30)->nullable()->default('RECEIVED');
                $table->boolean('is_read')->default(false)->index();
                $table->json('raw_data')->nullable();
                $table->timestamp('received_at')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbox_messages');
    }
};
