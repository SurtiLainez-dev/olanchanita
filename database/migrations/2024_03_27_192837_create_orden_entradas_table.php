<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_entradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_entrada_articulo_id')->constrained();
            $table->foreignId('proveedor_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('sucursal_id')->constrained();
            $table->date('fecha_creacion');
            $table->string('observacion', 200)->nullable();
            $table->string('codigo', 15);
            $table->boolean('pendiente')->default(false);
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
        Schema::dropIfExists('orden_entradas');
    }
}
