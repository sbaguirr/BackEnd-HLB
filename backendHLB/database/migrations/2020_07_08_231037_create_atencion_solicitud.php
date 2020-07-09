<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtencionSolicitud extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atencion_solicitudes', function (Blueprint $table) {
            $table->bigIncrements('id_atencion');
            $table->date('fecha_atencion');
            $table->string('observacion');  
            $table->unsignedBigInteger('id_solicitud');
            $table->string('id_usuario')->nullable();
            $table->timestamps();

            $table->foreign('id_usuario')
            ->references('usuario')->on('usuarios')
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
        Schema::dropIfExists('atencion_solicitudes');
    }
}
