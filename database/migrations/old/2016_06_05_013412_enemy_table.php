<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnemyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enemy', function (Blueprint $table) {
            $table->foreign('chara_id')->references('fix_id')->on('unit');
            $table->foreign('act_table1')->references('fix_id')->on('enemy_action_table');
            $table->foreign('act_table2')->references('fix_id')->on('enemy_action_table');
            $table->foreign('act_table3')->references('fix_id')->on('enemy_action_table');
            $table->foreign('act_table4')->references('fix_id')->on('enemy_action_table');
            $table->foreign('act_table5')->references('fix_id')->on('enemy_action_table');
            $table->foreign('act_table6')->references('fix_id')->on('enemy_action_table');
            $table->foreign('act_table7')->references('fix_id')->on('enemy_action_table');
            $table->foreign('act_table8')->references('fix_id')->on('enemy_action_table');
            $table->foreign('act_first')->references('fix_id')->on('enemy_action_param');
            $table->foreign('act_dead')->references('fix_id')->on('enemy_action_param');
            $table->foreign('ability1')->references('fix_id')->on('enemy_ability');
            $table->foreign('ability2')->references('fix_id')->on('enemy_ability');
            $table->foreign('ability3')->references('fix_id')->on('enemy_ability');
            $table->foreign('ability4')->references('fix_id')->on('enemy_ability');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enemy', function (Blueprint $table) {
            //
        });
    }
}
