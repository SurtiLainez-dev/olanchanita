<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectrizImpresionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directriz_impresions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->constrained();
            $table->string('contador_inicial',8);
            $table->string('contador_final',8);
            $table->string('codigo_post_contador',11);
            $table->date('fecha_emision');
            $table->date('fecha_final');
            $table->boolean('estado');
            $table->string('cai',40);
            $table->integer('tipo')->comment('1. recibos. 2.factura');
            $table->foreignId('user_id')->constrained();
            $table->string('inicio_contador',8);
            $table->string('contador_actual',8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('directriz_impresions');
    }
}
