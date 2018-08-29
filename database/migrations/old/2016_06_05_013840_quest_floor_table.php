<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuestFloorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
        {DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::table('quest_floor', function (Blueprint $table) {
            $table->foreign('quest_id')->references('fix_id')->on('quest');
            $table->foreign('boss_group_id')->references('fix_id')->on('enemy_group');
            $table->foreign('enemy_group_id_1')->references('fix_id')->on('enemy_group');
            $table->foreign('enemy_group_id_2')->references('fix_id')->on('enemy_group');
            $table->foreign('enemy_group_id_3')->references('fix_id')->on('enemy_group');
            $table->foreign('enemy_group_id_4')->references('fix_id')->on('enemy_group');
            $table->foreign('enemy_group_id_5')->references('fix_id')->on('enemy_group');
            $table->foreign('enemy_group_id_6')->references('fix_id')->on('enemy_group');
            $table->foreign('enemy_group_id_7')->references('fix_id')->on('enemy_group');
            $table->foreign('trap_group_id_1')->references('fix_id')->on('panel_group');
            $table->foreign('trap_group_id_2')->references('fix_id')->on('panel_group');
            $table->foreign('trap_group_id_3')->references('fix_id')->on('panel_group');
            $table->foreign('trap_group_id_4')->references('fix_id')->on('panel_group');
            $table->foreign('trap_group_id_5')->references('fix_id')->on('panel_group');
            $table->foreign('trap_group_id_6')->references('fix_id')->on('panel_group');
            $table->foreign('trap_group_id_7')->references('fix_id')->on('panel_group');
            $table->foreign('item_group_id_1')->references('fix_id')->on('panel_group');
            $table->foreign('item_group_id_2')->references('fix_id')->on('panel_group');
            $table->foreign('item_group_id_3')->references('fix_id')->on('panel_group');
            $table->foreign('item_group_id_4')->references('fix_id')->on('panel_group');
            $table->foreign('item_group_id_5')->references('fix_id')->on('panel_group');
            $table->foreign('item_group_id_6')->references('fix_id')->on('panel_group');
            $table->foreign('item_group_id_7')->references('fix_id')->on('panel_group');
            $table->foreign('heal_group_id_1')->references('fix_id')->on('panel_group');
            $table->foreign('heal_group_id_2')->references('fix_id')->on('panel_group');
            $table->foreign('heal_group_id_3')->references('fix_id')->on('panel_group');
            $table->foreign('heal_group_id_4')->references('fix_id')->on('panel_group');
            $table->foreign('heal_group_id_5')->references('fix_id')->on('panel_group');
            $table->foreign('heal_group_id_6')->references('fix_id')->on('panel_group');
            $table->foreign('heal_group_id_7')->references('fix_id')->on('panel_group');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quest_floor', function (Blueprint $table) {
            //
        });
    }
}
