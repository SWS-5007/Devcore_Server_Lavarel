<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourcesTables extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('type', 128)->nullable()->index();
            $table->string('display_type', 32)->nullable()->index();
            $table->string('owner_type', 128)->nullable()->index();
            $table->string('owner_id', 128)->nullable()->index();
            $table->string('mime_type', 32)->nullable()->index();
            $table->string('uri')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('size')->default(0);
            $table->boolean('enabled')->default(true);
            $table->string('visibility', 32)->default('public');
            $table->json('properties')->nullable();
            $table->timestamps();

            $table->index(['owner_type', 'owner_id']);
        });

        Schema::create('resource_collections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('collection_type', 128)->index();
            $table->string('owner_type', 128)->index();
            $table->string('owner_id', 128)->index();
            $table->string('section_id', 128)->index();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->json('properties')->nullable();
            $table->timestamps();

            $table->index(['owner_type', 'owner_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resources');
        Schema::dropIfExists('resource_collections');
    }
}
