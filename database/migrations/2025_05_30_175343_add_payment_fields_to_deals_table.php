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
        Schema::table('deals', function (Blueprint $table) {
            $table->decimal('deposit_amount')->nullable();
            $table->boolean('is_deposit_paid')->default(false);
            $table->timestamp('deposit_paid_at')->nullable();

            $table->decimal('final_payment_amount')->nullable();
            $table->boolean('is_final_paid')->default(false);
            $table->timestamp('final_paid_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn([
                'deposit_amount',
                'is_deposit_paid',
                'deposit_paid_at',
                'final_payment_amount',
                'is_final_paid',
                'final_paid_at',
            ]);
        });
    }
};
