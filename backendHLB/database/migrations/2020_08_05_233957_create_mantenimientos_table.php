<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMantenimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->bigIncrements('id_mantenimiento');
            $table->string('titulo');
            $table->string('tipo');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('observacion_falla');
            $table->string('estado_fisico');
            $table->string('actividad_realizada');
            $table->string('observacion');
            $table->unsignedBigInteger('id_equipo');
            $table->unsignedBigInteger('id_solicitud')->nullable();
            $table->string('realizado_por')->nullable();
            $table->timestamps();

            $table->foreign('id_equipo')
            ->references('id_equipo')->on('equipos')
            ->onDelete('cascade')
            ->onUpdate('cascade'); 

            $table->foreign('id_solicitud')
            ->references('id_solicitud')->on('solicitudes')
            ->onDelete('set null')
            ->onUpdate('cascade'); 

            $table->foreign('realizado_por')
            ->references('username')->on('users')
            ->onDelete('set null')
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
        Schema::dropIfExists('mantenimientos');
    }
}
