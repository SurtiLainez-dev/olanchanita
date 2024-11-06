<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('directriz_impresion_id')->constrained();
            $table->boolean('tipo')->comment('0: credito, 1:contado');
            $table->boolean('cancelado');
            $table->string('contador',30);
            $table->unsignedBigInteger('comanda_id')->nullable();
            $table->foreign('comanda_id')->on('comandas')->references('id');
            $table->foreignId('forma_pago_id')->constrained();
            $table->string('comentario',200)->nullable();
            $table->double('grabado_1',9,2);
            $table->double('grabado_2',9,2);
            $table->double('descuento',9,2);
            $table->double('exonerado',9,2);
            $table->double('impuesto_1',9,2);
            $table->double('impuesto_2',9,2);
            $table->double('total_exento',9,2);
            $table->double('total',9,2);
            $table->double('cobrado',9,2);
            $table->double('cambio',9,2);
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
        Schema::dropIfExists('facturas');
    }
}
