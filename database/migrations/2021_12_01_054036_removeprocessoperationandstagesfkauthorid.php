<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Removeprocessoperationandstagesfkauthorid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('process_operations', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->change();
            $table->dropForeign('process_operations_author_id_foreign');
            $table->foreign('author_id')->references('id')->on('users')->onDelete("SET NULL");
        });

        Schema::table('process_phases', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->change();
            $table->dropForeign('process_phases_author_id_foreign');
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

        Schema::table('process_operations', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->change();
            $table->dropForeign('process_operations_author_id_foreign');
            $table->foreign('author_id')->references('id')->on('users')->onDelete("CASCADE");
        });

        Schema::table('process_phases', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->change();
            $table->dropForeign('process_phases_author_id_foreign');
            $table->foreign('author_id')->references('id')->on('users')->onDelete("CASCADE");
        });

    }
}
