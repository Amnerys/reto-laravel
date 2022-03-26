<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *'foto' => 'foto.jpg',
    'categoria_id' => rand(1,100),
    'tarifa'
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('cod_producto');
            $table->string('nombre_producto');
            $table->string('descripcion_producto')->nullable();
            $table->string('foto')->nullable();
            $table->integer('categoria_cod');
            $table->string('tarifa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
