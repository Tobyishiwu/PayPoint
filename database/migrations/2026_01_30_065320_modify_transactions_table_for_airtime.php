<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Change type from enum to string to allow 'airtime' and 'transfer'
            $table->string('type')->change();

            // Add description to store details like "MTN Airtime to 080..."
            $table->string('description')->nullable()->after('amount');

            // Add status for future API integration
            $table->string('status')->default('completed')->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('type', ['deposit', 'withdrawal'])->change();
            $table->dropColumn(['description', 'status']);
        });
    }
};
