<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuestRequirementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quest_requirement', function (Blueprint $table) {
            $table->foreign('require_unit_00')->references('fix_id')->on('unit');
            $table->foreign('require_unit_01')->references('fix_id')->on('unit');
            $table->foreign('require_unit_02')->references('fix_id')->on('unit');
            $table->foreign('require_unit_03')->references('fix_id')->on('unit');
            $table->foreign('require_unit_04')->references('fix_id')->on('unit');
            $table->foreign('fix_unit_00_id')->references('fix_id')->on('unit');
            $table->foreign('fix_unit_01_id')->references('fix_id')->on('unit');
            $table->foreign('fix_unit_02_id')->references('fix_id')->on('unit');
            $table->foreign('fix_unit_03_id')->references('fix_id')->on('unit');
            $table->foreign('fix_unit_04_id')->references('fix_id')->on('unit');
            $table->foreign('fix_unit_00_link_id')->references('fix_id')->on('unit');
            $table->foreign('fix_unit_01_link_id')->references('fix_id')->on('unit');
            $table->foreign('fix_unit_02_link_id')->references('fix_id')->on('unit');
            $table->foreign('fix_unit_03_link_id')->references('fix_id')->on('unit');
            $table->foreign('fix_unit_04_link_id')->references('fix_id')->on('unit');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quest_requirement', function (Blueprint $table) {
            //
        });
    }
}
