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
    Schema::table('transactions', function (Blueprint $table) {

        if (!Schema::hasColumn('transactions', 'reference')) {
            $table->string('reference')->nullable()->after('id');
        }

        if (!Schema::hasColumn('transactions', 'service')) {
            $table->string('service')->nullable()->after('reference');
        }

        if (!Schema::hasColumn('transactions', 'title')) {
            $table->string('title')->nullable()->after('service');
        }

        if (!Schema::hasColumn('transactions', 'meta')) {
            $table->json('meta')->nullable()->after('balance_after');
        }

        if (!Schema::hasColumn('transactions', 'status')) {
            $table->string('status')->default('success')->after('meta');
        }

    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'reference',
                'service',
                'title',
                'meta',
                'status',
            ]);
        });
    }
};
