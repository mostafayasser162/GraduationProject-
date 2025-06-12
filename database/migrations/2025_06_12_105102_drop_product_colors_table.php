<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('product_colors');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        Schema::create('product_colors', function ($table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('color_name');
            $table->string('color_code')->nullable();
            $table->timestamps();

            // Optional: re-add foreign key if needed
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
};
