<?php

use App\Models\CompanyTool;
use App\Models\CompanyToolPrice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyToolsPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_tool_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_tool_id');
            $table->foreign('company_tool_id')->references('id')->on('company_tools')->onDelete('CASCADE');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->unsignedBigInteger('tool_id');
            $table->foreign('tool_id')->references('id')->on('tools')->onDelete('RESTRICT');
            $table->string('name')->nullable();
            $table->enum('status', CompanyToolPrice::STATUS)->default('ACTIVE')->index();
            $table->enum('price_model', CompanyToolPrice::PRICE_MODELS)->default('LICENSE')->index();
            $table->unsignedInteger('price_frequency')->default(1);
            $table->enum('price_interval', CompanyToolPrice::PRICE_INTERVALS)->default('YEAR')->index();
            $table->unsignedBigInteger('price')->default(1);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('parent_type')->nullable();
            $table->dateTime('expiration')->nullable();
            $table->dateTime('next_schedule_process')->nullable();
            $table->json('properties')->nullable();
            $table->timestamps();

            $table->index(['parent_id', 'parent_type']);
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
