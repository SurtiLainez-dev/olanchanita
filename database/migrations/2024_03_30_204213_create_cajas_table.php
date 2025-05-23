<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('codigo',10);
            $table->integer('num_caja');
            $table->boolean('estado_cierre')->default(false);
            $table->boolean('activa')->default(false);
            $table->double('total',9,2)->default(0);
            $table->string('password',200)->nullable();
            $table->double('saldo_acumulado')->default(0);
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
        Schema::dropIfExists('cajas');
    }
}
