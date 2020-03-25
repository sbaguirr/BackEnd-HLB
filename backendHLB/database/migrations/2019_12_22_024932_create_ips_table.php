<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ips', function (Blueprint $table) {
            $table->bigIncrements('id_ip');
            $table->string('estado');
            $table->string('direccion_ip');
            $table->string('hostname');
            $table->string('subred');
            $table->string('fortigate');
            $table->string('observacion');
            $table->integer('maquinas_adicionales');
            $table->string('nombre_usuario')->nullable();
            $table->string('encargado_registro');
            $table->timestamps();

            $table->foreign('encargado_registro')
            ->references('usuario')->on('usuarios')
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
        Schema::dropIfExists('ips');
    }
}
