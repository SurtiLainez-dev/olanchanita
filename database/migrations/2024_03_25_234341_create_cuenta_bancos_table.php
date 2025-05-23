<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuentaBancosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuenta_bancos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 100);
            $table->foreignId('tipo_cuenta_banco_id')->constrained();
            $table->string('num',30);
            $table->foreignId('banco_id')->constrained();
            $table->double('total',12,2);
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
        Schema::dropIfExists('cuenta_bancos');
    }
}
