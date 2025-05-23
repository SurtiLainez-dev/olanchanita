<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturaProveedorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_proveedors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orden_entrada_id')->nullable();
            $table->foreignId('proveedor_id')->constrained();
            $table->string('referencia');
            $table->double('saldo_inicial');
            $table->double('saldo_actual');
            $table->date('fecha');
            $table->string('file')->nullable();
            $table->foreign('orden_entrada_id')->on('orden_entradas')->references('id');
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
        Schema::dropIfExists('factura_proveedors');
    }
}
