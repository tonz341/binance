<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPricesTableAddRsi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->float('rsi_14_1d')->after('symbol')->nullable();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->float('btc_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->dropColumn('rsi_14_1d');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('btc_price');
        });
    }
}