<?php

use App\Enums\FactoryResponse\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('factory_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factory_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('request_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('description');
            $table->integer('price');
            $table->longText('image')->nullable();
            $table->string('status')->default(Status::init());

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factory_responses');
    }
};
