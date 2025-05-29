<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeColorCodeNullableInProductColorsTable extends Migration
{
    public function up()
    {
        Schema::table('product_colors', function (Blueprint $table) {
            $table->string('color_code')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('product_colors', function (Blueprint $table) {
            $table->string('color_code')->nullable(false)->change();
        });
    }
}
