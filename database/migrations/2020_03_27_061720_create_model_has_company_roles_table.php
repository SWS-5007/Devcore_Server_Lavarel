<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelHasCompanyRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_has_company_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_role_id');
            $table->foreign('company_role_id')->references('id')->on('company_roles')->onDelete('CASCADE');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->json('properties')->nullable();
            $table->timestamps();

            $table->unique(['model_type', 'model_id', 'company_role_id'], 'model_has_company_roles_uq_owner');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_company_roles');
    }
}
