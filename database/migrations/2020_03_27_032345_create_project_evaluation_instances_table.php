<?php

use App\Models\ProjectEvaluationInstance;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectEvaluationInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_evaluation_instances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->unsignedBigInteger('process_id')->nullable();
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('CASCADE');
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
            $table->unsignedBigInteger('project_stage_id');
            $table->foreign('project_stage_id')->references('id')->on('project_stages')->onDelete('CASCADE');
            $table->enum('status', ProjectEvaluationInstance::STATUS)->default('OPEN');
            $table->dateTime('started_at')->useCurrent();
            $table->dateTime('ends_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->dateTime('evaluation_period_start')->nullable();
            $table->dateTime('evaluation_period_end')->nullable();
            $table->bigInteger('total_gains')->default(0);
            $table->bigInteger('total_losses')->default(0);
            $table->bigInteger('consolidated_value')->default(0);
            $table->unsignedInteger('total_evaluations')->default(0);
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
        Schema::dropIfExists('project_evaluation_instances');
    }
}
