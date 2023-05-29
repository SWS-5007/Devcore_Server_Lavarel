<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MilestoneScoreInstancesMilestoneUserFkSetNullOnDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('milestone_score_instances', function (Blueprint $table) {
            $table->dropForeign(["milestone_user"]);
            $table->dropColumn("milestone_user");
        });

        Schema::table('milestone_score_instances', function (Blueprint $table) {
            $table->unsignedBigInteger('milestone_user')->nullable();
            $table->foreign('milestone_user')->references('id')->on('milestone_users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('milestone_score_instances', function (Blueprint $table) {
            $table->dropForeign(["milestone_user"]);
            $table->dropColumn("milestone_user");
        });

        Schema::table('milestone_score_instances', function (Blueprint $table) {
            $table->unsignedBigInteger('milestone_user');
            $table->foreign('milestone_user')->references('id')->on('milestone_users')->onDelete('CASCADE');
        });



    }
}
