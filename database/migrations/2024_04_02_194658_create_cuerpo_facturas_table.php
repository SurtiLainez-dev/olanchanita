<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuerpoFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuerpo_facturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('articulo_id')->nullable();
            $table->foreign('articulo_id')->on('articulos')->references('id');
            $table->foreignId('factura_id')->constrained();
            $table->integer('cantidad');
            $table->double('precio',9,2);
            $table->double('total',9,2);
            $table->foreignId('impuesto_id')->constrained();
            $table->unsignedBigInteger('combo_id')->nullable();
            $table->foreign('combo_id')->on('combos')->references('id');
            $table->json('precio_sides')->nullable()->default(null);
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
        Schema::dropIfExists('cuerpo_facturas');
    }
}
