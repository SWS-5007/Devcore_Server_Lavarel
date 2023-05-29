<?php

use App\Models\IssueEffect;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssueEffectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue_effects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->index();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->string('effect_time')->nullable()->default(0);
            $table->unsignedInteger('effect_value')->nullable()->default(0);
            $table->unsignedBigInteger('issue_active_id')->nullable();
            $table->foreign('issue_active_id')->references('id')->on('issues')->onDelete('CASCADE');
            $table->enum('status', IssueEffect::EFFECT_STATUS)->default('CREATED')->index();
            $table->unsignedBigInteger('effect_id')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->timestamps();
        });


        Schema::create('issue_effect_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('effect_id');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->foreign('effect_id')->references('id')->on('issue_effects')->onDelete('CASCADE');
            $table->string('effect_time')->nullable()->default(0);
            $table->unsignedInteger('effect_value')->nullable()->default(0);
            $table->unsignedBigInteger('company_role_id')->nullable();
            $table->unsignedBigInteger('process_id');
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('CASCADE');
            $table->string('parent_type')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();

            $table->timestamps();
            $table->index(['effect_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issue_effects');
        Schema::dropIfExists('issue_effect_templates');
    }
}
