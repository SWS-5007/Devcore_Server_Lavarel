<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdeaContents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idea_contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('content_type');
            $table->json('markup')->nullable();
            $table->unsignedBigInteger('idea_id')->nullable();
            $table->foreign('idea_id')->references('id')->on('ideas')->onDelete('CASCADE');
            $table->unsignedInteger('version')->default(0);
            $table->timestamps();
            $table->index(['idea_id', 'version', 'content_type']);
        });

        Schema::table('ideas', function (Blueprint $table) {
            $table->unsignedBigInteger('content_id')->nullable();
            $table->foreign('content_id')->references('id')->on('idea_contents')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


        Schema::table('ideas', function (Blueprint $table) {
            $table->dropForeign('ideas_content_id_foreign');
            $table->dropColumn('content_id');
        });

        Schema::dropIfExists('idea_contents');

    }
}
