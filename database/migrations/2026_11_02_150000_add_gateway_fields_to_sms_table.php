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
            if (!Schema::hasColumn('sms', 'gateway_txnid')) {
                $table->string('gateway_txnid')->nullable();
            }
            if (!Schema::hasColumn('sms', 'gateway_response_code')) {
                $table->string('gateway_response_code')->nullable();
            }
            if (!Schema::hasColumn('sms', 'mno_txnid')) {
                $table->string('mno_txnid')->nullable();
            }
            if (!Schema::hasColumn('sms', 'request_id')) {
                $table->string('request_id')->nullable();
            }
            if (!Schema::hasColumn('sms', 'operator')) {
                $table->string('operator')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms', function (Blueprint $table) {
            $columns = ['gateway_txnid', 'gateway_response_code', 'mno_txnid', 'request_id', 'operator'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('sms', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
