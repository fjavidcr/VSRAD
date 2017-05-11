<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsIntoMensajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mensajes', function (Blueprint $table) {
            $table->integer('id_cliente')->nullable();
            $table->integer('id_comercial')->nullable();
            $table->integer('id_tecnico')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mensajes', function (Blueprint $table) {
            $table->dropColumn('id_cliente');
            $table->dropColumn('id_comercial');
            $table->dropColumn('id_tecnico');
        });
    }
}
