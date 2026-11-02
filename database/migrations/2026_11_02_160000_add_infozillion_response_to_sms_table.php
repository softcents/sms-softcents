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
        Schema::table('sms', function (Blueprint $table) {
            if (!Schema::hasColumn('sms', 'infozillion_response')) {
                $table->text('infozillion_response')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms', function (Blueprint $table) {
            if (Schema::hasColumn('sms', 'infozillion_response')) {
                $table->dropColumn('infozillion_response');
            }
        });
    }
};
