<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuerpoOrdenSalidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuerpo_orden_salidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_salida_id')->constrained();
            $table->foreignId('articulo_id')->constrained();
            $table->double('cantidad',9,2);
            $table->double('precio',9,2);
            $table->string('comentario',250);
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
        Schema::dropIfExists('cuerpo_orden_salidas');
    }
}
