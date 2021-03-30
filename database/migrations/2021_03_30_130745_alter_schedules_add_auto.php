<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSchedulesAddAuto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->float('rsi')->default(0);
            $table->integer('rsi_period')->default(14);
            $table->string('rsi_interval')->nullable();
            $table->integer('rsi_cycle')->default(0);
            $table->boolean('auto_cycle')->default(false);
            $table->float('average_price')->default(0);
            $table->float('uncommitted_shares')->default(0);
            $table->integer('target_sell')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('rsi');
            $table->dropColumn('rsi_period');
            $table->dropColumn('rsi_interval');
            $table->dropColumn('auto_cycle');
            $table->dropColumn('average_price');
            $table->dropColumn('uncommitted_shares');
            $table->dropColumn('target_sell');
            $table->dropColumn('rsi_cycle');
        });
        //
    }
}
