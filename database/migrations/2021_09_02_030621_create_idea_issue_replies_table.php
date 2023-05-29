<?php

use App\Models\IdeaIssueReply;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdeaIssueRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idea_issue_replies', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->text('description')->nullable();
            $table->enum('status', IdeaIssueReply::TYPES)->default('FEEDBACK')->index();
            $table->enum('type', IdeaIssueReply::REFERENCES)->default('IDEA')->index();
            $table->unsignedBigInteger('issue_id')->nullable();
            $table->foreign('issue_id')->references('id')->on('issues')->onDelete('CASCADE');
            $table->unsignedBigInteger('idea_id')->nullable();
            $table->foreign('idea_id')->references('id')->on('ideas')->onDelete('CASCADE');
            $table->foreign('idea_issue_id')->references('id')->on('idea_issues')->onDelete('CASCADE');
            $table->unsignedBigInteger('idea_issue_id')->nullable();
            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('idea_issue_replies');
    }
}
