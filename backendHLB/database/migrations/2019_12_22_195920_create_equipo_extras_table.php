<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipoExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipo_extras', function (Blueprint $table) {
            $table->bigIncrements('id_eqext');
            $table->string('num_serie');
            $table->string('marca');
            $table->string('modelo');
            $table->string('estado_operativo');
            $table->string('descripcion');
            $table->bigInteger('id_equipo')->unsigned();
            $table->timestamps();

            $table->foreign('id_equipo')
            ->references('id_equipo')->on('equipos')
            ->onDelete('cascade')
            ->onUpdate('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipo_extras');
    }
}
