<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->constrained();
            $table->foreignId('forma_pago_id')->constrained();
            $table->string('referencia',500);
            $table->integer('tipo_documento')->comment('1:factura, 2:recibo factura, 3:ingreso, 4:Egreso, 5:Factura Credito');
            $table->unsignedBigInteger('factura_id')->nullable();
            $table->foreign('factura_id')->references('id')->on('facturas');
            $table->double('total',9,2);
            $table->boolean('tipo')->comment('1:egresos - 0:ingresos');
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
        Schema::dropIfExists('historial_cajas');
    }
}
