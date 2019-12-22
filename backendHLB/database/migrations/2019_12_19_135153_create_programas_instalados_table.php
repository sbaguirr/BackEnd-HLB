<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramasInstaladosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programas_instalados', function (Blueprint $table) {
            $table->bigIncrements('id_programa');
            $table->string('nombre');
            $table->string('codigo');
            $table->string('observacion');
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
        Schema::dropIfExists('programas_instalados');
    }
}
