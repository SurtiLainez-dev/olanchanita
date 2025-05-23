<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReciboIdHisotrialCaja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historial_cajas', function (Blueprint $table) {
            $table->unsignedBigInteger('recibo_id')->nullable();
            $table->foreign('recibo_id')->on('recibos')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historial_cajas', function (Blueprint $table) {
            $table->dropColumn(['recibo_id']);
        });
    }
}
