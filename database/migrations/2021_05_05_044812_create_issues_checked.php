<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesChecked extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('issues', function (Blueprint $table) {
            if(Schema::hasColumn('issues', 'checked_issue')) return;
                $table->boolean('checked_issue')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('issues', 'checked_issue')) {
            Schema::table("issues", function($table) {
                $table->dropColumn('checked_issue');
            });
        }

    }
}
