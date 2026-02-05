<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('transactions', function (Blueprint $table) {
        // 1. Add the column as nullable first so existing rows don't crash
        $table->foreignId('user_id')->nullable()->after('id');
    });

    // 2. Assign all existing transactions to the first user (ID 1)
    // so the database has a valid link
    \DB::table('transactions')->update(['user_id' => 1]);

    Schema::table('transactions', function (Blueprint $table) {
        // 3. Now make it required and add the constraint
        $table->foreignId('user_id')->nullable(false)->change();
        $table->foreign(['user_id'])->references('id')->on('users')->onDelete('cascade');
    });
}

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
