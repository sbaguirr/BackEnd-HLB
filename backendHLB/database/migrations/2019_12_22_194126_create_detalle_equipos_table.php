<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_equipos', function (Blueprint $table) {
            $table->bigIncrements('id_detalle');
            $table->string('so');
            $table->integer('services_pack');
            $table->string('tipo_so');
            $table->string('nombre_pc');
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
        Schema::dropIfExists('detalle_equipos');
    }
}
