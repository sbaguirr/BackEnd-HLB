<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordatoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recordatorios', function (Blueprint $table) {
            $table->bigIncrements('id_recordatorio');
            $table->time('hora_recordatorio',0);
            $table->date('fecha_recordatorio');
            $table->string('estado');
            $table->unsignedBigInteger('id_mantenimiento');
            $table->timestamps();


            $table->foreign('id_mantenimiento')
            ->references('id_mantenimiento')->on('mantenimientos')
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
        Schema::dropIfExists('recordatorios');
    }
}
