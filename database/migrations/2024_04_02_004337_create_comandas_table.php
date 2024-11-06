<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComandasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comandas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->double('total',9,2);
            $table->foreignId('user_id')->constrained();
            $table->foreignId('mesa_id')->constrained();
            $table->boolean('estado')->default(true)->comment('abierta:  1 - cerrada: 0');
            $table->string('comentario', 500)->nullable();
            $table->double('saldo_actual',9,2)->nullable();
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
        Schema::dropIfExists('comandas');
    }
}
