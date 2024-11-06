<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuerpoOrdenEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuerpo_orden_entradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_entrada_id')->constrained();
            $table->foreignId('articulo_id')->constrained();
            $table->double('cantidad',9,2);
            $table->boolean('is_compuesto')->default(false);
            $table->foreignId('estado_articulo_id')->constrained();
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
        Schema::dropIfExists('cuerpo_orden_entradas');
    }
}
