<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrationAerometrics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aerometrics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('station_id')->index();
            $table->float('temperature');
            $table->float('pressure');
            $table->float('altitude');
            $table->float('insolation');
            $table->float('humidity');
            $table->float('co');
            $table->float('co2');
            $table->float('methane');
            $table->float('butane');
            $table->float('propane');
            $table->float('benzene');
            $table->float('ethanol');
            $table->float('alcohol');
            $table->float('hydrogen');
            $table->float('ozone');
            $table->float('cng');
            $table->float('lpg');
            $table->float('coal_gas');
            $table->float('smoke');
            $table->timestamps();

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('aerometrics');
    }
}
