<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sender_ips', function (Blueprint $table) {
            if (!Schema::hasColumn('sender_ips', 'note')) {
                $table->string('note')->nullable()->after('ip_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sender_ips', function (Blueprint $table) {
            if (Schema::hasColumn('sender_ips', 'note')) {
                $table->dropColumn('note');
            }
        });
    }
};
