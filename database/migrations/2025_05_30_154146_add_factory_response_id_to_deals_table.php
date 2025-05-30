<?php

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
        Schema::table('deals', function (Blueprint $table) {
            $table->foreignId('factory_response_id')
                ->nullable()
                ->constrained('factory_responses')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->after('factory_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropForeign(['factory_response_id']);
            $table->dropColumn('factory_response_id');
        });
    }
};
