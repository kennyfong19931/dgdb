<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnemyActionParamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enemy_action_param', function (Blueprint $table) {
            $table->foreign('status_ailment1')->references('fix_id')->on('status_ailment');
            $table->foreign('status_ailment2')->references('fix_id')->on('status_ailment');
            $table->foreign('status_ailment3')->references('fix_id')->on('status_ailment');
            $table->foreign('status_ailment4')->references('fix_id')->on('status_ailment');
            $table->foreign('add_fix_id')->references('fix_id')->on('enemy_action_param');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enemy_action_param', function (Blueprint $table) {
            //
        });
    }
}
