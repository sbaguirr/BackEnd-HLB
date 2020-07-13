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
            $table->ipAddress('direccion_ip')->unique();
            $table->ipAddress('hostname');
            $table->ipAddress('subred');
            $table->ipAddress('fortigate');
            $table->text('observacion')->nullable();
            $table->unsignedTinyInteger('maquinas_adicionales');
            $table->string('nombre_usuario', 100)->nullable();
            $table->string('encargado_registro', 100);
            $table->timestampsTz(0);

            $table->foreign('encargado_registro')
            ->references('username')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            // $table->foreign('id_estado_equipo')
            // ->references('id_estado_equipo')->on('estado_equipo')
            // ->onDelete('cascade')
            // ->onUpdate('cascade')
            ;

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
