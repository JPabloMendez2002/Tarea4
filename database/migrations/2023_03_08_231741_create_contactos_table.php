<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Contactos', function (Blueprint $table) {
            $table->id('IdContacto');
            $table->unsignedBigInteger('IdUsuario');
            $table->string('Nombre',10);
            $table->string('Apellidos',20);
            $table->string('Facebook',20);
            $table->string('Instagram',20);
            $table->string('Twitter',20);
            $table->timestamps(); 
            $table->foreign('IdUsuario')->references('IdUsuario')->on('Usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Contactos');
    }
};
