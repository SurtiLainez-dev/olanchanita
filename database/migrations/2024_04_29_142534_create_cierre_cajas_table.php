<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCierreCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cierre_cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->constrained();
            $table->json('resumen_forma_pagos')->nullable();
            $table->json('resumen')->nullable();
            $table->double('total',9,2)->default(0)->comment('total con tarjeta, depositos y efectivo');
            $table->double('total_efectivo',9,2)->default(0)->comment('total con efectivo');
            $table->double('egresos',9,2)->default(0)->comment('total de salidas');
            $table->double('efectivo_final_dia',9,2)->default(0);
            $table->double('efectivo_declarado',9,2)->default(0);
            $table->double('descuadre',9,2)->default(0);
            $table->string('cierre_firmado')->nullable();
            $table->double('total_tarjeta',9,2)->default(0);
            $table->date('fecha');
            $table->boolean('revisado')->default(false);
            $table->string('comentario_revision',250)->nullable();
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
        Schema::dropIfExists('cierre_cajas');
    }
}
