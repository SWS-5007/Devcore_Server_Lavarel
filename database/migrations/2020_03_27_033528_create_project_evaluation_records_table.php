<?php

use App\Models\ProjectEvaluationRecord;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectEvaluationRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_evaluation_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->unsignedBigInteger('process_id');
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('CASCADE');
            $table->unsignedBigInteger('process_stage_id');
            $table->foreign('process_stage_id')->references('id')->on('process_stages')->onDelete('CASCADE');
            $table->unsignedBigInteger('project_idea_id');
            $table->foreign('project_idea_id')->references('id')->on('project_ideas')->onDelete('CASCADE');
            $table->unsignedBigInteger('project_user_id');
            $table->foreign('project_user_id')->references('user_id')->on('project_users')->onDelete('CASCADE');
            $table->unsignedBigInteger('idea_id');
            $table->foreign('idea_id')->references('id')->on('ideas')->onDelete('CASCADE');
            $table->unsignedBigInteger('evaluation_instance_id');
            $table->foreign('evaluation_instance_id')->references('id')->on('project_evaluation_instances')->onDelete('CASCADE');
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
            $table->unsignedBigInteger('project_stage_id');
            $table->foreign('project_stage_id')->references('id')->on('project_stages')->onDelete('CASCADE');
            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->enum('status', ProjectEvaluationRecord::STATUS)->default('PENDING')->index();
            $table->enum('time_unit', ProjectEvaluationRecord::DIMENSIONS)->default('TOTAL');
            $table->bigInteger('time_value')->default(0);
            $table->bigInteger('time_total')->default(0);
            $table->enum('money_unit', ProjectEvaluationRecord::DIMENSIONS)->default('TOTAL');
            $table->bigInteger('money_value')->default(0);
            $table->bigInteger('money_total')->default(0);
            $table->bigInteger('total_value')->default(0);
            $table->bigInteger('author_yearly_costs')->default(0);
            $table->bigInteger('yearly_costs_money')->default(0);
            $table->text('comment')->nullable();
            $table->json('properties')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            //$table->unique(['evaluation_instance_id', 'process_stage_id', 'author_id'], 'uq_evaluation_instance_id_author_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_evaluation_records');
    }
}
