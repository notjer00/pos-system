<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Backfill existing products with defaults
        Product::query()->update([
            'gender' => 'unisex',
            'size_system' => 'apparel',
        ]);

        // Make columns non-nullable (they already have defaults from first migration)
        Schema::table('products', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female', 'unisex'])->default('unisex')->change();
            $table->enum('size_system', ['apparel', 'us_footwear', 'eu_footwear', 'uk_footwear'])->default('apparel')->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female', 'unisex'])->nullable()->change();
            $table->enum('size_system', ['apparel', 'us_footwear', 'eu_footwear', 'uk_footwear'])->nullable()->change();
        });
    }
};
