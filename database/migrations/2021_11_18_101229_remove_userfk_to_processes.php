<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUserfkToProcesses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('processes', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->change();
            $table->dropForeign('processes_author_id_foreign');
            $table->foreign('author_id')->references('id')->on('users')->onDelete("SET NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('processes', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->change();
            $table->dropForeign('processes_author_id_foreign');
            $table->foreign('author_id')->references('id')->on('users')->onDelete("CASCADE");
        });
    }
}
