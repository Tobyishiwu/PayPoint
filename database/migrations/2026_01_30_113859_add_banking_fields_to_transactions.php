<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Add all missing columns that the controllers are looking for
            if (!Schema::hasColumn('transactions', 'category')) {
                $table->string('category')->nullable()->after('type');
            }
            if (!Schema::hasColumn('transactions', 'balance_after')) {
                $table->decimal('balance_after', 15, 2)->after('amount');
            }
            if (!Schema::hasColumn('transactions', 'description')) {
                $table->text('description')->nullable()->after('balance_after');
            }
            if (!Schema::hasColumn('transactions', 'reference')) {
                $table->string('reference')->unique()->nullable()->after('description');
            }
            if (!Schema::hasColumn('transactions', 'status')) {
                $table->string('status')->default('completed')->after('reference');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['category', 'balance_after', 'description', 'reference', 'status']);
        });
    }
};
