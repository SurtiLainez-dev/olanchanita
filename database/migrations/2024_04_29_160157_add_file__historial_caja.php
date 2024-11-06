<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileHistorialCaja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historial_cajas', function (Blueprint $table) {
            $table->string('file_cargado')->nullable();
            $table->unsignedBigInteger('retirada_efectivo_id')->nullable();
            $table->foreign('retirada_efectivo_id')->on('retirada_efectivos')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historial_cajas', function (Blueprint $table) {
            $table->dropColumn(['file_cargado']);
        });
    }
}
