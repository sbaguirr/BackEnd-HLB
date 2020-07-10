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
            $table->string('codigo')->unique();
            $table->string('version')->nullable();
            $table->string('editor')->nullable();
            $table->string('observacion')->nullable();
            $table->string('encargado_registro');
            $table->timestamps();

            $table->foreign('encargado_registro')
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
        Schema::dropIfExists('programas_instalados');
    }
}
