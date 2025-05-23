<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuerpoOrdenPorcionadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuerpo_orden_porcionados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_porcionado_id')->constrained();
            $table->foreignId('articulo_id')->constrained();
            $table->double('cantidad_actual_articulo',9,2);
            $table->double('cantidad',9,2);
            $table->double('cantidad_nueva',9,2);
            $table->string('comentario');
            $table->foreignId('sucursal_id')->constrained();
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
        Schema::dropIfExists('cuerpo_orden_porcionados');
    }
}
