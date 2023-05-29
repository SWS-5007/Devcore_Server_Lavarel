<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IdeaIssuesContentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('idea_issues', function (Blueprint $table) {
            $table->unsignedBigInteger('idea_content_id')->nullable();
            $table->foreign('idea_content_id')->references('id')->on('idea_contents')->onDelete('SET NULL');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('idea_issues', function (Blueprint $table) {
            $table->dropForeign("idea_issues_idea_content_id");
            $table->dropColumn("idea_content_id");
        });
    }
}
