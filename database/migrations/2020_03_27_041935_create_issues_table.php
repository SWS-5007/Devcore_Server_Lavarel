<?php

use App\Models\Issue;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
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
            $table->unsignedBigInteger('project_stage_id')->nullable();
            $table->foreign('project_stage_id')->references('id')->on('project_stages')->onDelete('CASCADE');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('parent_type')->nullable();
            $table->text('description');
            $table->string('title')->index();
            $table->enum('type', Issue::TYPES)->default('IMPROVEMENT')->index();
            $table->enum('time_unit', Issue::DIMENSIONS)->default('TOTAL');
            $table->bigInteger('time_value')->default(0);
            $table->bigInteger('time_total')->default(0);
            $table->enum('money_unit', Issue::DIMENSIONS)->default('TOTAL');
            $table->bigInteger('money_value')->default(0);
            $table->bigInteger('money_total')->default(0);
            $table->bigInteger('total_value')->default(0);
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
        Schema::dropIfExists('issues');
    }
}
