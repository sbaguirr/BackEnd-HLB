<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitud extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->bigIncrements('id_solicitud');
            $table->time('hora_realizacion', 0);
            $table->date('fecha_realizacion');
            $table->string('observacion');
            $table->string('estado');
            $table->string('prioridad');
            $table->string('tipo');
            $table->unsignedBigInteger('id_firma')->nullable();
            $table->string('id_usuario')->nullable();
            $table->timestamps();

            $table->foreign('id_usuario')
            ->references('username')->on('users')
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
        Schema::dropIfExists('solicitudes');
    }
}
