<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // If the column already exists, we stop here.
        // This allows Laravel to mark the migration as 'completed'
        // in the migrations table without throwing an error.
        if (Schema::hasColumn('transactions', 'response_payload')) {
            return;
        }

        Schema::table('transactions', function (Blueprint $table) {
            $table->longText('response_payload')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('transactions', 'response_payload')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('response_payload');
            });
        }
    }
};
