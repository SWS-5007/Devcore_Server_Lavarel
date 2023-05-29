<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        File::cleanDirectory(storage_path('app/public/cache'));

        Model::unguard();
        //disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call(CurrenciesTableSeeder::class);
        $this->call(IndustriesTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(CompanyRolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ProcessTableSeeder::class);
        $this->call(ToolTableSeeder::class);
        $this->call(IdeaTableSeeder::class);
        $this->call(ProjectTableSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
