<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('usuario', 25);
            $table->foreignId('colaborador_id')->constrained();
            $table->string('email', 50);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('tipo_usuario_id')->constrained();
            $table->boolean('estado');
            $table->string('num_ingreso', 7)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
