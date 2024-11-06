<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrecioArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precio_articulos', function (Blueprint $table) {
            $table->id();
            $table->double('precio',9,2);
            $table->double('precio_costo',9,2);
            $table->double('precio_descuento',9,2);
            $table->double('ganancia',9,2);
            $table->foreignId('articulo_id')->constrained();
            $table->boolean('is_activo')->default(true);
            $table->foreignId('impuesto_id')->constrained();
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
        Schema::dropIfExists('precio_articulos');
    }
}
