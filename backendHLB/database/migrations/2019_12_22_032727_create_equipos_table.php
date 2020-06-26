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
            $table->string('estado_operativo');
            $table->string('codigo')->unique();
            $table->string('tipo_equipo');
            $table->string('modelo')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('numero_serie')->nullable();
            $table->unsignedBigInteger('id_marca')->nullable();
            $table->string('encargado_registro')->nullable();
            $table->unsignedBigInteger('componente_principal')->nullable();
            $table->unsignedBigInteger('ip')->nullable();
            $table->string('asignado')->nullable();
            $table->timestamps();

            $table->foreign('id_marca')
            ->references('id_marca')->on('marcas')
            ->onDelete('set null')
            ->onUpdate('cascade'); 
            
            $table->foreign('encargado_registro')
            ->references('usuario')->on('usuarios')
            ->onDelete('cascade')
            ->onUpdate('cascade'); 

            $table->foreign('asignado')
            ->references('cedula')->on('empleados')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('componente_principal')
            ->references('id_equipo')->on('equipos')
            ->onDelete('set null')
            ->onUpdate('cascade'); 

            $table->foreign('ip')
            ->references('id_ip')->on('ips')
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
        Schema::dropIfExists('equipos');
    }
}
