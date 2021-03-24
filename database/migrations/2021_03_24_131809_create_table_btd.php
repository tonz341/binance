<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBtd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('type')->after('schedule_id')->default('DCA');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->string('type')->default('DCA');
            $table->integer('trigger_percentage')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('trigger_percentage');
        });
    }
}