<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdeaToolIdeas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ideas', function (Blueprint $table) {
            if(Schema::hasColumn('ideas', 'company_tool_ids')) return;
            $table->json('company_tool_ids')->nullable();
        });

        if(!Schema::hasTable('model_has_company_tools')){
            Schema::create('model_has_company_tools', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('company_tool_id');
                $table->foreign('company_tool_id')->references('id')->on('company_tools')->onDelete('CASCADE');
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
                $table->json('properties')->nullable();
                $table->timestamps();
                $table->unique(['model_type', 'model_id', 'company_tool_id'], 'model_has_company_tools_uq_owner');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ideas', function (Blueprint $table) {
            if(!Schema::hasColumn('ideas', 'company_tool_ids')) return;
            $table->dropColumn('company_tool_ids');
        });

        Schema::dropIfExists('model_has_company_tools');

    }
}
