<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveProjectStageFKProcessStage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_stages', function (Blueprint $table) {
            $table->dropForeign('project_stages_stage_id_foreign');
            $table->foreign('stage_id')->references('id')->on('process_stages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_stages', function (Blueprint $table) {
            $table->dropForeign('project_stages_stage_id_foreign');
            $table->foreign('stage_id')->references('id')->on('process_stages')->onDelete('CASCADE');
        });
    }

}
