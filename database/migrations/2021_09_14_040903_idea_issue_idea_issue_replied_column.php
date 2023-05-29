<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IdeaIssueIdeaIssueRepliedColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('idea_issues', function (Blueprint $table) {
            $table->boolean('replied')->default(false);
        });

        Schema::table('ideas', function (Blueprint $table) {
            $table->boolean('replied')->default(false);
        });

        Schema::table('issues', function (Blueprint $table) {
            $table->boolean('replied')->default(false);
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
            $table->dropColumn('replied');
        });

        Schema::table('ideas', function (Blueprint $table) {
            $table->dropColumn('replied');
        });

        Schema::table('issues', function (Blueprint $table) {
            $table->dropColumn('replied');
        });
    }
}
