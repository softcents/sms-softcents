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
        if (!Schema::hasColumn('sms', 'source')) {
            Schema::table('sms', function (Blueprint $table) {
                $table->string('source', 20)->default('web')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('sms', 'source')) {
            Schema::table('sms', function (Blueprint $table) {
                $table->dropColumn('source');
            });
        }
    }
};
