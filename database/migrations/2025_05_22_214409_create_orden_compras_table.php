<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained();
            $table->string('cod',25);
            $table->foreignId('user_id')->constrained();
            $table->string('comentario')->nullable();
            $table->date('fecha_entrega');
            $table->double('total',9,2);
            $table->foreignId('sucursal_id')->constrained();
            $table->enum('estado',['PENDIENTE','RECHAZADA','RECIBIDO']);
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
        Schema::dropIfExists('orden_compras');
    }
}
