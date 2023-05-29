<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Removeideasfktousers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }


    public function up()
    {
        Schema::table('ideas', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->change();
            $table->dropForeign('ideas_author_id_foreign');
            $table->foreign('author_id')->references('id')->on('users')->onDelete("SET NULL");
        });

        Schema::table('issues', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->change();
            $table->dropForeign('issues_author_id_foreign');
            $table->foreign('author_id')->references('id')->on('users')->onDelete("SET NULL");
        });

        Schema::table('idea_issues', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->change();
            $table->dropForeign('idea_issues_author_id_foreign');
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

        Schema::table('ideas', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->change();
            $table->dropForeign('ideas_author_id_foreign');
            $table->foreign('author_id')->references('id')->on('users')->onDelete("CASCADE");
        });

        Schema::table('issues', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->change();
            $table->dropForeign('issues_author_id_foreign');
            $table->foreign('author_id')->references('id')->on('users')->onDelete("CASCADE");
        });

        Schema::table('idea_issues', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->change();
            $table->dropForeign('idea_issues_author_id_foreign');
            $table->foreign('author_id')->references('id')->on('users')->onDelete("CASCADE");
        });
    }
}
