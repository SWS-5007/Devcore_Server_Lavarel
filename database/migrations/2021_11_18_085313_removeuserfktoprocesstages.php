<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Removeuserfktoprocesstages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('process_stages', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->change();
            $table->dropForeign('process_stages_author_id_foreign');
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
        Schema::table('process_stages', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->change();
            $table->dropForeign('process_stages_author_id_foreign');
            $table->foreign('author_id')->references('id')->on('users')->onDelete("CASCADE");
        });
    }
}
