<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleComponentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_componentes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dato');
            $table->string('campo');
           // $table->bigInteger('id_componente')->unsigned();
            $table->timestamps();

            $table->unsignedBigInteger('id_equipo')->nullable();

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
        Schema::dropIfExists('detalle_componentes');
    }
}
