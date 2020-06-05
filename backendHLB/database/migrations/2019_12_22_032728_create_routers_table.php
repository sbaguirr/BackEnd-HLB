<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routers', function (Blueprint $table) {
            $table->bigIncrements('id_router');
            $table->string('nombre');
            $table->string('pass');
            $table->string('puerta_enlace')->nullable();
            $table->string('usuario');
            $table->string('clave');
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
        Schema::dropIfExists('routers');
    }
}
