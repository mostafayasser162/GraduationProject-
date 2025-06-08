<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('factory_responses', function (Blueprint $table) {
            $table->string('estimated_delivery_time')->nullable()->after('price');
        });
    }
    
    public function down()
    {
        Schema::table('factory_responses', function (Blueprint $table) {
            $table->dropColumn('estimated_delivery_time');
        });
    }
    
};
