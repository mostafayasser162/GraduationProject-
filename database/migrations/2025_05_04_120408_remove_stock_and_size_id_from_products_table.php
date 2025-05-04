<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStockAndSizeIdFromProductsTable extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // First drop the foreign key constraint on size_id (if it exists)
            $table->dropForeign(['size_id']);

            // Then drop the columns
            $table->dropColumn('stock');
            $table->dropColumn('size_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock')->after('price');

            $table->unsignedBigInteger('size_id')->nullable()->after('sub_category_id');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
        });
    }
}
