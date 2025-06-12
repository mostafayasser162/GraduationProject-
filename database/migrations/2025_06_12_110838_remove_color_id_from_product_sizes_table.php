<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_sizes', function (Blueprint $table) {
            $table->dropForeign(['color_id']); // Drop foreign key first (if exists)
            $table->dropColumn('color_id');    // Then drop the column
        });
    }

    public function down(): void
    {
        Schema::table('product_sizes', function (Blueprint $table) {
            $table->unsignedBigInteger('color_id');

            // Re-add foreign key if needed
            // $table->foreign('color_id')->references('id')->on('product_colors')->onDelete('cascade');
        });
    }
};

