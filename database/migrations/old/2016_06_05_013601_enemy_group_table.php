<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnemyGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enemy_group', function (Blueprint $table) {
            $table->foreign('enemy_id_1')->references('fix_id')->on('enemy');
            $table->foreign('enemy_id_2')->references('fix_id')->on('enemy');
            $table->foreign('enemy_id_3')->references('fix_id')->on('enemy');
            $table->foreign('enemy_id_4')->references('fix_id')->on('enemy');
            $table->foreign('enemy_id_5')->references('fix_id')->on('enemy');
            $table->foreign('enemy_id_6')->references('fix_id')->on('enemy');
            $table->foreign('enemy_id_7')->references('fix_id')->on('enemy');
            $table->foreign('chain_id')->references('fix_id')->on('enemy_group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enemy_group', function (Blueprint $table) {
            //
        });
    }
}
