<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropUnique(['product_id', 'size', 'color']);
            $table->string('size')->nullable()->change();
            $table->string('color')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('size')->nullable(false)->change();
            $table->string('color')->nullable(false)->change();
            $table->unique(['product_id', 'size', 'color']);
        });
    }
};
