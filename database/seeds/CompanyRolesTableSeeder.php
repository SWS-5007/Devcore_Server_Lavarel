<?php

use App\Models\Company;
use App\Models\CompanyRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CompanyRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Management', 'Design', 'I+D', 'Development', 'Testing'];

        //CompanyRole::truncate();

        //clean section storage
        Storage::disk('uploads')->deleteDirectory("company-roles");

        $companies = Company::all();

     //   foreach ($companies as $company) {
       //     foreach ($roles as $rolename) {
                CompanyRole::create([
                    'name' => "Management",
                    'company_id' => 1,
                ]);
          //  }
       // }
    }
}
