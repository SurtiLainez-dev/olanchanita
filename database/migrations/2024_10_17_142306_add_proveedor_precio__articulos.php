<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProveedorPrecioArticulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('precio_articulos', function (Blueprint $table) {
            $table->unsignedBigInteger('proveedor_id')->nullable()->default(null);
            $table->foreign('proveedor_id')->on('proveedors')->references('id');
            $table->string('comentario')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('precio_articulos', function (Blueprint $table) {
            $table->dropColumn(['proveedor_id','comentario']);
        });
    }
}
