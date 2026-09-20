<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('category');
            $table->string('unit')->nullable()->default('Piece')->after('brand');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('variant_name')->nullable()->after('product_id');
        });

        // Backfill variant_name from existing size/footwear_size/color data
        $variants = DB::table('product_variants')->get();
        foreach ($variants as $variant) {
            $parts = [];
            $size = $variant->footwear_size ?? $variant->size;
            if ($size) {
                $parts[] = $size;
            }
            if ($variant->color) {
                $parts[] = $variant->color;
            }
            $variantName = ! empty($parts) ? implode(' / ', $parts) : 'Standard';

            DB::table('product_variants')
                ->where('id', $variant->id)
                ->update(['variant_name' => $variantName]);
        }
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('variant_name');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['brand', 'unit']);
        });
    }
};
