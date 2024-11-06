<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetiradaEfectivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retirada_efectivos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cuenta_banco_id')->nullable();
            $table->foreign('cuenta_banco_id')->on('cuenta_bancos')->references('id');
            $table->double('total',9,2)->default(0);
            $table->foreignId('user_id')->constrained();
            $table->string('comentario');
            $table->string('file')->nullable();
            $table->enum('tipo_salida',['POR DEPOSITO','RETIRADA NORMAL','GASTO']);
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
        Schema::dropIfExists('retirada_efectivos');
    }
}
