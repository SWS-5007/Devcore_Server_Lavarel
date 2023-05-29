<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IdeaIssueRepliesTypeAuthorId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('idea_issue_replies', function (Blueprint $table) {
            if(Schema::hasColumn('idea_issue_replies', 'type_author_id')) return;
            $table->unsignedBigInteger('type_author_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('idea_issue_replies', function (Blueprint $table) {
            $table->dropColumn('type_author_id');
        });
    }
}

