<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quest', function (Blueprint $table) {
            $table->foreign('area_id')->references('fix_id')->on('area');
            $table->foreign('clear_unit')->references('fix_id')->on('unit');
            $table->foreign('quest_requirement_id')->references('fix_id')->on('quest_requirement');
            $table->foreign('boss_chara_id')->references('fix_id')->on('unit');
            $table->foreign('e_chara_id_0')->references('fix_id')->on('unit');
            $table->foreign('e_chara_id_1')->references('fix_id')->on('unit');
            $table->foreign('e_chara_id_2')->references('fix_id')->on('unit');
            $table->foreign('e_chara_id_3')->references('fix_id')->on('unit');
            $table->foreign('e_chara_id_4')->references('fix_id')->on('unit');
            $table->foreign('boss_ability_1')->references('fix_id')->on('enemy_ability');
            $table->foreign('boss_ability_2')->references('fix_id')->on('enemy_ability');
            $table->foreign('boss_ability_3')->references('fix_id')->on('enemy_ability');
            $table->foreign('boss_ability_4')->references('fix_id')->on('enemy_ability');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quest', function (Blueprint $table) {
            //
        });
    }
}
