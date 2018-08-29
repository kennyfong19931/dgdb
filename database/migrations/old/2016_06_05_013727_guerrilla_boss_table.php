<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GuerrillaBossTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guerrilla_boss', function (Blueprint $table) {
            $table->foreign('enemy_group_id')->references('fix_id')->on('enemy_group');
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
        Schema::table('guerrilla_boss', function (Blueprint $table) {
            //
        });
    }
}
