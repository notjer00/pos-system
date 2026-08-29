<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commission_logs', function (Blueprint $table) {
            $table->boolean('is_voided')->default(false)->after('commission_earned');
        });
    }

    public function down(): void
    {
        Schema::table('commission_logs', function (Blueprint $table) {
            $table->dropColumn('is_voided');
        });
    }
};
