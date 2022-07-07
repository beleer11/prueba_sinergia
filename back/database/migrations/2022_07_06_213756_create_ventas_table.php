<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venta', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("descripcion", 40);
            $table->bigInteger("id_producto")->unsigned();
            $table->bigInteger("id_cliente")->unsigned();
            $table->date("fecha_compra");

            //Foreign key
            $table->foreign('id_producto')->references('id')->on('producto');
            $table->foreign('id_cliente')->references('id')->on('cliente');
            $table->softDeletes();
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
        Schema::dropIfExists('venta');
    }
}
