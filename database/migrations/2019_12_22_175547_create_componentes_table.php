<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('componentes', function (Blueprint $table) {
            $table->bigIncrements('id_componente');
            $table->string('tipo_componente');
            $table->string('marca');
            $table->string('modelo');
            $table->string('numero_serie');
            $table->string('estado_operativo');
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
        Schema::dropIfExists('componentes');
    }
}
