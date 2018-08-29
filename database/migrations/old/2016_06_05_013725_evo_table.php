<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EvoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evo', function (Blueprint $table) {
            $table->foreign('unit_id_pre')->references('fix_id')->on('unit');
            $table->foreign('unit_id_after')->references('fix_id')->on('unit');
            $table->foreign('unit_id_parts1')->references('fix_id')->on('unit');
            $table->foreign('unit_id_parts2')->references('fix_id')->on('unit');
            $table->foreign('unit_id_parts3')->references('fix_id')->on('unit');
            $table->foreign('unit_id_parts4')->references('fix_id')->on('unit');
            $table->foreign('quest_id')->references('fix_id')->on('quest');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evo', function (Blueprint $table) {
            //
        });
    }
}
