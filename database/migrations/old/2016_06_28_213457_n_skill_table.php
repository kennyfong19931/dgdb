<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('n_skill', function (Blueprint $table) {
            $table->foreign('skill_boost_id')->references('fix_id')->on('skill_boost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('n_skill', function (Blueprint $table) {
            //
        });
    }
}
