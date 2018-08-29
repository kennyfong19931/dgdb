<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnemyActionTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enemy_action_table', function (Blueprint $table) {
            $table->foreign('action_param_id1')->references('fix_id')->on('enemy_action_param');
            $table->foreign('action_param_id2')->references('fix_id')->on('enemy_action_param');
            $table->foreign('action_param_id3')->references('fix_id')->on('enemy_action_param');
            $table->foreign('action_param_id4')->references('fix_id')->on('enemy_action_param');
            $table->foreign('action_param_id5')->references('fix_id')->on('enemy_action_param');
            $table->foreign('action_param_id6')->references('fix_id')->on('enemy_action_param');
            $table->foreign('action_param_id7')->references('fix_id')->on('enemy_action_param');
            $table->foreign('action_param_id8')->references('fix_id')->on('enemy_action_param');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enemy_action_table', function (Blueprint $table) {
            //
        });
    }
}
