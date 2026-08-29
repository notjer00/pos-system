<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female', 'unisex'])->default('unisex')->after('category');
            $table->enum('size_system', ['apparel', 'us_footwear', 'eu_footwear', 'uk_footwear'])->default('apparel')->after('gender');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['gender', 'size_system']);
        });
    }
};
