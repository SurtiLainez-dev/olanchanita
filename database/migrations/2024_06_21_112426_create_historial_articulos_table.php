<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_articulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('articulo_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('detalle',500);
            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
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
        Schema::dropIfExists('historial_articulos');
    }
}
