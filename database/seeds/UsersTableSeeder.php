<?php

use App\Lib\Utils;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker =  \Faker\Factory::create();
        // DB::statement("TRUNCATE users CASCADE");
        //  User::truncate();
        $data = [];

        //clean section storage
        Storage::disk('uploads')->deleteDirectory("users");

        //generate root
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'root@devcore.test',
            'is_super_admin' => true,
            'email_verified_at' => now(),
            'password' =>  Hash::make('supersecret123@!'),
            'remember_token' => Str::random(10),
        ]);




        /* $companies = company::all()->take(4);
             $globalindex = 1;
             foreach ($companies as $company) {
                 $companyroles = $company->companyroles;
                 for ($i = 1; $i < 50; $i++) {
                     $user = user::create([
                         'first_name' => $faker->firstname,
                         'last_name' => $faker->lastname,
                         'email' => "user{$globalindex}@devcore.test",
                         'is_super_admin' => false,
                         'email_verified_at' => now(),
                         'password' =>  hash::make('secret'),
                         'remember_token' => str::random(10),
                         'company_id' => $company->id,
                         'company_role_id' => $companyroles->random()->id,
                         'yearly_costs' => 1200000,
                     ]);
                     if ($i === 1) {
                         $user->assignrole('company admin');
                     } elseif ($i < 4) {
                         $user->assignrole('company manager');
                     } else {
                         $user->assignrole('user');
                     }
                     // $user->companies()->attach([
                     //     'company_id' => $company->id,
                     //     'user_id' => $user->id,
                     //     'yearly_costs' => $faker->numberbetween(30000, 120000)
                     // ]);
                     $globalindex++;
                 }
             }*/
    }
}
