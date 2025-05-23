<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuerpoComandasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuerpo_comandas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('articulo_id')->nullable();
            $table->unsignedBigInteger('combo_id')->nullable();
            $table->foreign('articulo_id')->on('articulos')->references('id');
            $table->foreign('combo_id')->on('combos')->references('id');
            $table->foreignId('comanda_id')->constrained();
            $table->boolean('is_combo');
            $table->integer('cant');
            $table->double('total',9,2);
            $table->boolean('imprimible');
            $table->integer('cant_pendiente');
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
        Schema::dropIfExists('cuerpo_comandas');
    }
}
