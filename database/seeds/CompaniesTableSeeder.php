<?php

use App\Models\Company;
use App\Models\Currency;
use App\Models\Industry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::truncate();

        $faker =  \Faker\Factory::create();

        //clean section storage
        Storage::disk('uploads')->deleteDirectory("companies");

        $currencies=Currency::where('code', 'EUR')->orWhere('code', 'USD')->get();
        $industries=Industry::all();

        for ($i = 0; $i < 50; $i++) {
            Company::create([
                'name'=>$faker->company,
                'default_lang'=>config('app.default_locale'),
                'currency_code'=>$faker->randomElement($currencies)->code,
                'industry_id'=>$faker->randomElement($industries)->id,
            ]);
        }
    }
}
