<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key
            $table->decimal('lat', 10, 8)->nullable(); // Latitude
            $table->decimal('lng', 11, 8)->nullable(); // Longitude
            $table->string('address', 255); // Required address
            $table->string('city', 255)->nullable(); // Optional city
            $table->timestamps(); // created_at and updated_at

            // Foreign key constraint
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->index('user_id'); // Optional, since foreign key already creates it
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
}
