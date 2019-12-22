<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramaEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programa_equipos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha_instalacion');
            $table->bigInteger('id_programa')->unsigned();
            $table->bigInteger('id_equipo')->unsigned();
            $table->timestamps();

            $table->foreign('id_programa')
            ->references('id_programa')->on('programas_instalados')
            ->onDelete('cascade')
            ->onUpdate('cascade'); 

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
        Schema::dropIfExists('programa_equipos');
    }
}
