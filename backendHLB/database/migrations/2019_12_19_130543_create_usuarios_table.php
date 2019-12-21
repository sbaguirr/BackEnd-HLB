<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->string('usuario');
            $table->string('contrasena');
            $table->bigInteger('id_rol')->unsigned();
            $table->string('cedula');
            $table->timestamps();

            $table->primary('usuario');
            $table->foreign('id_rol')
            ->references('id_rol')->on('roles')
            ->onDelete('cascade')
            ->onUpdate('cascade'); 

            $table->foreign('cedula')
            ->references('cedula')->on('empleados')
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
        Schema::dropIfExists('usuarios');
    }
}
