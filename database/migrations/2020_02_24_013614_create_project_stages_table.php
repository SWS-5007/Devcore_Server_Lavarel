<?php

use App\Models\ProjectStage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_stages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
            $table->unsignedBigInteger('process_id');
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('CASCADE');
            $table->unsignedBigInteger('stage_id');
            $table->unsignedBigInteger('d_order');
            $table->foreign('stage_id')->references('id')->on('process_stages')->onDelete('CASCADE');
            $table->enum('status', array_values(ProjectStage::STAGE_STATUS))->default('STARTED');
            $table->dateTime('next_schedule_process')->nullable();
            $table->bigInteger('total_gains')->default(0);
            $table->bigInteger('total_losses')->default(0);
            $table->bigInteger('consolidated_value')->default(0);
            $table->bigInteger('total_evaluations')->default(0);
            $table->dateTime('started_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();
        });

        //reference to project_stage on ideas
        Schema::table('ideas', function (Blueprint $table) {
            $table->unsignedBigInteger('project_stage_id')->nullable();
            $table->foreign('project_stage_id')->references('id')->on('project_stages')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_stages');
    }
}
