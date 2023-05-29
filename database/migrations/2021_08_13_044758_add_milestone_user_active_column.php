<?php

use App\Models\MilestoneUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMilestoneUserActiveColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('milestone_users', function (Blueprint $table) {
            $table->enum('status', MilestoneUser::MILESTONE_USER_TYPES)->default('ACTIVE')->index();

        });
    }

    public function down()
    {
        Schema::table('milestone_users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
