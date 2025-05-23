<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemOrdenComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_orden_compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_compra_id')->constrained();
            $table->unsignedBigInteger('articulo_id')->nullable();
            $table->foreign('articulo_id')->on('articulos')->references('id');
            $table->double('cant',9,2);
            $table->double('precio',9,2);
            $table->double('total',9,2);
            $table->string('comentario')->nullable();
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
        Schema::dropIfExists('item_orden_compras');
    }
}
