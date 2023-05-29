<?php

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->foreign('author_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->unsignedBigInteger('process_id')->nullable();
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('CASCADE');
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('budget')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->enum('evaluation_type', Project::EVALUATION_TYPES)->default('STAGE_FINISH')->index();
            $table->unsignedInteger('evaluation_interval_amount')->nullable();
            $table->enum('evaluation_interval_unit', Project::EVALUATION_INTERVAL_UNITS)->nullable();
            $table->enum('type', Project::PROJECT_TYPES)->default('NORMAL')->index();
            $table->enum('status', array_values(Project::PROJECT_STATUS))->default('NOT_STARTED')->index();
            $table->bigInteger('total_gains')->default(0);
            $table->bigInteger('total_losses')->default(0);
            $table->bigInteger('consolidated_value')->default(0);
            $table->bigInteger('total_evaluations')->default(0);
            $table->unsignedInteger('d_order')->default(1);
            $table->json('properties')->nullable();
            $table->timestamps();

            $table->unique(['process_id', 'name']);
        });

        //create project reference on company_tools
        Schema::table('company_tools', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
        });

        //create project reference on ideas
        Schema::table('ideas', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
