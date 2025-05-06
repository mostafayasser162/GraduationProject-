<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAndRecreateOrderItemsTable extends Migration
{
    public function up()
    {
        // Drop the existing order_items table
        Schema::dropIfExists('order_items');
        
        // Recreate the order_items table
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned(); // Primary key
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('product_size_id')->unsigned()->nullable();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
            
            // Add foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_size_id')->references('id')->on('product_sizes')->onDelete('set null');
        });
    }

    public function down()
    {
        // Drop the order_items table
        Schema::dropIfExists('order_items');
    }
}
