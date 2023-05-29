<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('project_tools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->unsignedBigInteger('tool_id');
            $table->foreign('tool_id')->references('id')->on('tools')->onDelete('CASCADE');
            $table->unsignedBigInteger('project_stage_id');
            $table->foreign('project_stage_id')->references('id')->on('project_stages')->onDelete('CASCADE');
            $table->unsignedBigInteger('company_tool_id');
            $table->foreign('company_tool_id')->references('id')->on('company_tools')->onDelete('CASCADE');
            $table->unsignedBigInteger('project_id');
            $table->bigInteger('total_gains')->default(0);
            $table->bigInteger('total_losses')->default(0);
            $table->bigInteger('consolidated_value')->default(0);
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
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
        Schema::dropIfExists('project_tools');
    }
}
