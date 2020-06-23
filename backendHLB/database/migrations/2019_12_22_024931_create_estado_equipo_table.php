<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadoEquipoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estado_equipo', function (Blueprint $table) {
            $table->bigIncrements('id_estado_equipo');
            $table->string('nombre', 100)->nullable($value = false);
            $table->string('abreviatura', 100)->nullable($value = false);

            $table->timestampsTz(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('estado_equipo');
    }
}
