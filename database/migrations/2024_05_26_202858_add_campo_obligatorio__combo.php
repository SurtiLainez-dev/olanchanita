<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampoObligatorioCombo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('combos', function (Blueprint $table) {
            $table->boolean('is_sides')->default(false);
            $table->integer('cant_niveles_sides')->default(0);
            $table->json('cant_permitida_x_niveles')->default(null);
        });

        Schema::table('cuerpo_combos', function (Blueprint $table) {
            $table->integer('nivel')->default(0);
//            $table->boolean('is_obligatorio')->default(false);
            $table->double('precio_add')->comment('Si se agrega tiene un monto extra de lps')->default(0);
            $table->double('precio_extra')->comment('es el precio si se agrega como extra despues de superar la cantidad permitida del nivel')->default(0);
            $table->boolean('activo')->default(true);
            $table->boolean('default_nivel')->default(false)->comment('Es que se va a cargar por default en el combo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('combos', function (Blueprint $table) {
            $table->dropColumn(['is_sides','cant_niveles_sides','cant_permitida_x_niveles']);
        });
        Schema::table('cuerpo_combos', function (Blueprint $table) {
            $table->dropColumn(['nivel','precio_add','precio_extra','activo','default_nivel']);
        });
    }
}
