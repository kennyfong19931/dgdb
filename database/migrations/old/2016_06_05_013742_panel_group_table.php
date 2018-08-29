<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PanelGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('panel_group', function (Blueprint $table) {
            $table->foreign('panel_id_1')->references('fix_id')->on('panel');
            $table->foreign('panel_id_2')->references('fix_id')->on('panel');
            $table->foreign('panel_id_3')->references('fix_id')->on('panel');
            $table->foreign('panel_id_4')->references('fix_id')->on('panel');
            $table->foreign('panel_id_5')->references('fix_id')->on('panel');
            $table->foreign('panel_id_6')->references('fix_id')->on('panel');
            $table->foreign('panel_id_7')->references('fix_id')->on('panel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('panel_group', function (Blueprint $table) {
            //
        });
    }
}
