<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTitleFromIssue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('issues', function (Blueprint $table) {
            //
            if (Schema::hasColumn('issues', 'title')){

                Schema::table('issues', function (Blueprint $table) {
                    $table->dropColumn('title');

                });
            }
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('issue', function (Blueprint $table) {
            $table->string('title')->index();
        });
    }
}
