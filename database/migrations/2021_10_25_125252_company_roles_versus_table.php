<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompanyRolesVersusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_role_score_instances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_role_id')->nullable();
            $table->foreign('company_role_id')->references('id')->on('company_roles')->onDelete('CASCADE');
            $table->unsignedBigInteger('versus_role_id')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->bigInteger('consolidated_value')->default(0);
            $table->dateTime('versus_period_start')->nullable();
            $table->dateTime('versus_period_end')->nullable();
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
        Schema::dropIfExists('company_role_score_instances');
    }
}
