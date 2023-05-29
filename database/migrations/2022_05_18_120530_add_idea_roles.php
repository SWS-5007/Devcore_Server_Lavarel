<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdeaRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ideas', function (Blueprint $table) {
            if(Schema::hasColumn('ideas', 'company_role_ids')) return;
            $table->json('company_role_ids')->nullable();
        });
    }

    public function down()
    {
        Schema::table('ideas', function (Blueprint $table) {
            if(!Schema::hasColumn('ideas', 'company_role_ids')) return;
            $table->dropColumn('company_role_ids');
        });

    }
}
