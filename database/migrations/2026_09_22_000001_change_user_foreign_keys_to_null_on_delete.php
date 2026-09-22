<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite doesn't support dropForeign or nullable column changes via Blueprint.
            // Recreate tables with correct schema.
            $this->recreateWithSQLite();
        } else {
            $this->recreateWithMySql();
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $this->recreateWithSQLite();
        } else {
            Schema::table('sales_transactions', function (Blueprint $table) {
                $table->unsignedBigInteger('cashier_id')->nullable(false)->change();
            });

            Schema::table('commission_logs', function (Blueprint $table) {
                $table->dropForeign('commission_logs_user_id_foreign');
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
            });

            Schema::table('commission_logs', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            });

            Schema::table('stock_movements', function (Blueprint $table) {
                $table->dropForeign('stock_movements_created_by_foreign');
                $table->unsignedBigInteger('created_by')->nullable(false)->change();
            });

            Schema::table('stock_movements', function (Blueprint $table) {
                $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            });
        }
    }

    private function recreateWithMySql(): void
    {
        Schema::table('sales_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('cashier_id')->nullable()->change();
        });

        Schema::table('commission_logs', function (Blueprint $table) {
            $table->dropForeign('commission_logs_user_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        Schema::table('commission_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign('stock_movements_created_by_foreign');
            $table->unsignedBigInteger('created_by')->nullable()->change();
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    private function recreateWithSQLite(): void
    {
        // SQLite: recreate tables with nullable columns
        DB::statement('PRAGMA foreign_keys = OFF');

        // sales_transactions
        DB::statement('CREATE TABLE "sales_transactions_tmp" ("id" integer primary key autoincrement, "cashier_id" integer, "discount_id" integer, "total_amount" decimal not null, "status" varchar not null default \'completed\', "created_at" datetime, "updated_at" datetime)');
        DB::statement('INSERT INTO "sales_transactions_tmp" ("id", "cashier_id", "discount_id", "total_amount", "status", "created_at", "updated_at") SELECT "id", "cashier_id", "discount_id", "total_amount", "status", "created_at", "updated_at" FROM "sales_transactions"');
        DB::statement('DROP TABLE "sales_transactions"');
        DB::statement('ALTER TABLE "sales_transactions_tmp" RENAME TO "sales_transactions"');

        // commission_logs
        DB::statement('CREATE TABLE "commission_logs_tmp" ("id" integer primary key autoincrement, "sales_transaction_id" integer not null, "user_id" integer, "base_amount" decimal not null, "discount_applied" decimal not null default \'0.00\', "final_price" decimal not null, "commission_rate" decimal not null, "commission_earned" decimal not null, "is_voided" tinyint(1) not null default \'0\', "created_at" datetime, "updated_at" datetime)');
        DB::statement('INSERT INTO "commission_logs_tmp" ("id", "sales_transaction_id", "user_id", "base_amount", "discount_applied", "final_price", "commission_rate", "commission_earned", "is_voided", "created_at", "updated_at") SELECT "id", "sales_transaction_id", "user_id", "base_amount", "discount_applied", "final_price", "commission_rate", "commission_earned", "is_voided", "created_at", "updated_at" FROM "commission_logs"');
        DB::statement('DROP TABLE "commission_logs"');
        DB::statement('ALTER TABLE "commission_logs_tmp" RENAME TO "commission_logs"');

        // stock_movements
        DB::statement('CREATE TABLE "stock_movements_tmp" ("id" integer primary key autoincrement, "product_variant_id" integer not null, "type" varchar not null, "quantity_change" integer not null, "note" text, "created_by" integer, "created_at" datetime, "updated_at" datetime)');
        DB::statement('INSERT INTO "stock_movements_tmp" ("id", "product_variant_id", "type", "quantity_change", "note", "created_by", "created_at", "updated_at") SELECT "id", "product_variant_id", "type", "quantity_change", "note", "created_by", "created_at", "updated_at" FROM "stock_movements"');
        DB::statement('DROP TABLE "stock_movements"');
        DB::statement('ALTER TABLE "stock_movements_tmp" RENAME TO "stock_movements"');

        DB::statement('PRAGMA foreign_keys = ON');
    }
};
