<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Check if column doesn't exist before adding to prevent further errors
            if (!Schema::hasColumn('transactions', 'service_id')) {
                $table->string('service_id')->nullable()->after('category');
            }

            if (!Schema::hasColumn('transactions', 'variation_code')) {
                $table->string('variation_code')->nullable()->after('service_id');
            }

            if (!Schema::hasColumn('transactions', 'token')) {
                $table->string('token')->nullable()->after('amount');
            }

            if (!Schema::hasColumn('transactions', 'response_payload')) {
                $table->longText('response_payload')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['service_id', 'variation_code', 'token', 'response_payload']);
        });
    }
};
