<?php

use App\Models\Idea;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateIdeasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('ideas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->unsignedBigInteger('company_tool_id')->nullable();
            $table->foreign('company_tool_id')->references('id')->on('company_tools')->onDelete('CASCADE');
            $table->unsignedBigInteger('process_id');
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('CASCADE');
            $table->unsignedBigInteger('parent_id')->key()->nullable();
            #$table->foreign('parent_id')->references('id')->on('process_stages')->onDelete('CASCADE');
            $table->string('parent_type')->key()->nullable();
            $table->unsignedBigInteger('source_id')->key()->nullable();
            $table->string('source_type')->key()->nullable();
            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->enum('status', array_values(Idea::IDEA_STATUS))->default(Idea::IDEA_STATUS[0])->index();
            $table->enum('type', Idea::IDEA_TYPES)->default('PROCESS')->index();
            $table->bigInteger('total_gains')->default(0);
            $table->bigInteger('total_losses')->default(0);
            $table->bigInteger('consolidated_value')->default(0);
            $table->unsignedInteger('total_evaluations')->default(0);
            $table->json('properties')->nullable();
            $table->unsignedInteger('version')->default(0);
            $table->timestamps();

            $table->index(['parent_type', 'parent_id']);
            $table->index(['source_id', 'source_type']);
            //$table->unique(['parent_type', 'parent_id', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ideas');
    }
}
