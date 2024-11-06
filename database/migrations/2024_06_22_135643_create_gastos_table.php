<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_gasto_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->unsignedBigInteger('caja_id')->nullable();
            $table->unsignedBigInteger('banco_id')->nullable();
            $table->string('detalle');
            $table->double('total',9,2);
            $table->string('file')->nullable();
            $table->foreign('caja_id')->on('cajas')->references('id');
            $table->foreign('banco_id')->on('bancos')->references('id');
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
        Schema::dropIfExists('gastos');
    }
}
