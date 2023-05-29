<?php

use App\Models\ExperienceQuest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperienceTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('experience_quests', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('title')->nullable();
                $table->enum('user_type', ExperienceQuest::EXPERIENCE_USER_TYPES)->default('USER')->index();
                $table->unsignedBigInteger('company_id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
                $table->bigInteger('required_points')->default(1);
                $table->timestamps();
            });

            Schema::create('experience_users', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
                $table->bigInteger('experience_points')->default(1);
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
                $table->unsignedBigInteger('quest_id');
                $table->bigInteger('level')->default(1);
                $table->foreign('quest_id')->references('id')->on('experience_quests')->onDelete('CASCADE');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experience_tasks');
        Schema::dropIfExists('experience_users');
    }
}
