<?php

use App\Models\IdeaIssue;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdeaIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()
    {
        Schema::create('idea_issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->unsignedBigInteger('process_id');
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('CASCADE');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
            $table->unsignedBigInteger('idea_id');
            $table->foreign('idea_id')->references('id')->on('ideas')->onDelete('CASCADE');
            $table->unsignedBigInteger('project_stage_id')->nullable();
            $table->foreign('project_stage_id')->references('id')->on('project_stages')->onDelete('CASCADE');
            $table->unsignedBigInteger('parent_id');
            $table->string('parent_type');
            $table->string('title')->index();
            $table->text('description');
            $table->enum('type', IdeaIssue::TYPES)->default('IMPROVEMENT')->index();
            $table->enum('unit', IdeaIssue::UNITS)->default('MONEY')->index();
            $table->enum('dimension', IdeaIssue::DIMENSIONS)->default('TOTAL')->index();
            $table->bigInteger('value')->default(0);
            $table->bigInteger('value_money')->default(0);
            $table->json('properties')->nullable();
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
        Schema::dropIfExists('idea_issues');
    }
}
