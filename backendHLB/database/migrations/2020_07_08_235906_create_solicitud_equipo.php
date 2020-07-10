<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudEquipo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_equipos', function (Blueprint $table) {
            $table->bigIncrements('id_solicitud_equipo');
            $table->unsignedBigInteger('id_solicitud');
            $table->unsignedBigInteger('id_equipo');
            $table->timestamps();

            $table->foreign('id_equipo')
            ->references('id_equipo')->on('equipos')
            ->onDelete('cascade')
            ->onUpdate('cascade'); 

            $table->foreign('id_solicitud')
            ->references('id_solicitud')->on('solicitudes')
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
        Schema::dropIfExists('solicitud_equipos');
    }
}
