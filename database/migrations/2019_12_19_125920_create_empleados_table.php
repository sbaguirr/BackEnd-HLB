<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->string('cedula',10);
            $table->string('nombre');
            $table->string('apellido');
            $table->bigInteger('id_departamento')->unsigned();
            $table->timestamps();

            $table->primary('cedula');
            $table->foreign('id_departamento')
            ->references('id_departamento')->on('departamentos')
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
        Schema::dropIfExists('empleados');
    }
}
