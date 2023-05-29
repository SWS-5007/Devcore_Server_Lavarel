<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectIdeasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_ideas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
            $table->unsignedBigInteger('project_stage_id');
            $table->foreign('project_stage_id')->references('id')->on('project_stages')->onDelete('CASCADE');
            $table->unsignedBigInteger('idea_id');
            $table->foreign('idea_id')->references('id')->on('ideas')->onDelete('CASCADE');
            $table->unsignedBigInteger('process_id');
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('CASCADE');
            $table->unsignedBigInteger('process_stage_id');
            $table->foreign('process_stage_id')->references('id')->on('process_stages')->onDelete('CASCADE');
            $table->bigInteger('total_gains')->default(0);
            $table->bigInteger('total_losses')->default(0);
            $table->bigInteger('consolidated_value')->default(0);
            $table->bigInteger('total_evaluations')->default(0);
            $table->json('properties')->nullable();
            $table->timestamps();
            $table->unique(['project_id', 'idea_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_ideas');
    }
}
