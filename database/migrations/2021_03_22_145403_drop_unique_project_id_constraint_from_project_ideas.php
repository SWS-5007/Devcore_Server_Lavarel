<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUniqueProjectIdConstraintFromProjectIdeas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_ideas', function (Blueprint $table) {
            //
            //   $table->index(['idea_id']);
            //  $table->dropForeign(['idea_id']);
            $table->dropForeign('project_ideas_project_id_foreign');
            $table->dropUnique(['project_id_idea_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_ideas', function (Blueprint $table) {
            //
        });
    }
}
