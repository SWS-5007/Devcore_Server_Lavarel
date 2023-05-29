<?php

use App\Models\CompanyTool;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_tools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('company_tools')->onDelete('CASCADE');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->unsignedBigInteger('tool_id');
            $table->foreign('tool_id')->references('id')->on('tools')->onDelete('CASCADE');
            $table->enum('status', CompanyTool::STATUS)->default('ACTIVE')->index();
            $table->enum('price_model', CompanyTool::PRICE_MODELS)->default('LICENSE')->index();
            $table->unsignedBigInteger('costs')->default(0);
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
        Schema::dropIfExists('company_tools');
    }
}
