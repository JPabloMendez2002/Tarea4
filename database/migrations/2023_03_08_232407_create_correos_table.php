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
        Schema::create('Correos', function (Blueprint $table) {
            $table->id('IdCorreo');
            $table->unsignedBigInteger('IdContacto');
            $table->string('Correo',30);
            $table->timestamps(); 
            $table->foreign('IdContacto')->references('IdContacto')->on('Contactos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Correos');
    }
};
