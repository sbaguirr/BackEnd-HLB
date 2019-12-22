<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->bigIncrements('id_equipo');
            $table->date('fecha_registro');
            $table->string('estado_asignacion');
            $table->string('codigo');
            $table->string('tipo_equipo');
            $table->string('encargado_registro');
            $table->bigInteger('ip')->unsigned();
            $table->timestamps();

            $table->foreign('encargado_registro')
            ->references('usuario')->on('usuarios')
            ->onDelete('cascade')
            ->onUpdate('cascade'); 

            $table->foreign('ip')
            ->references('id_ip')->on('ips')
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
        Schema::dropIfExists('equipos');
    }
}
