<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->string('modelo',50);
            $table->string('nombre',50);
            $table->string('descripcion',250);
            $table->foreignId('sub_familia_articulo_id')->constrained();
            $table->string('codigo_sistema',25);
            $table->string('codigo_barras',70)->nullable();
            $table->foreignId('marca_id')->constrained();
            $table->double('precio_costo',9,2)->default(0);
            $table->double('stock_minimo',7,2)->default(0);
            $table->double('stock_maximo',7,2)->default(0);
            $table->string('foto')->nullable();
            $table->boolean('is_contable')->default(false);
            $table->boolean('is_visible')->default(false);
            $table->unsignedBigInteger('articulo_padre_id')->default(null)->nullable();
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
        Schema::dropIfExists('articulos');
    }
}
