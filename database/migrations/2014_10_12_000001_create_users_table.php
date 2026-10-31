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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->default('user');
            $table->string('email')->unique();
            $table->double('balance')->default(0);
            $table->double('masking_rate')->default(0);
            $table->double('non_masking_rate')->default(0);
            $table->boolean('allow_api')->default(0);
            $table->double('low_blnc_alrt')->default(0); // Low balance alert.
            $table->foreignId('plan_id')->nullable()->constrained()->cascadeOnDelete();
            $table->date('will_expire')->nullable();
            $table->string('phone')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('pending'); // active / pending / suspend
            $table->string('client_id');
            $table->string('client_secret')->nullable();
            $table->string('password');
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
