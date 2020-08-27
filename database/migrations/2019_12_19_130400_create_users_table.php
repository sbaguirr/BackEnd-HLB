<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('username');
            $table->string('password');
            $table->bigInteger('id_rol')->unsigned();
            $table->string('cedula');
            $table->string('estado')->default('A');
            $table->string('device_token')->nullable();
            $table->timestamps();

            $table->primary('username');
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
        Schema::dropIfExists('users');
    }
}
