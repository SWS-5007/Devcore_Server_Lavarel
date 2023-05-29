<?php

use App\Models\Company;
use App\Models\Tool;
use App\Models\CompanyTool;
use Illuminate\Database\Seeder;

class ToolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #Tool::truncate();
        #CompanyTool::truncate();
        $faker =  \Faker\Factory::create();
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        for ($i = 1; $i < 102; $i++) {
            $tool = new Tool();
            $tool->name = $faker->productName;
            $tool->save();
        }

        $allTools = Tool::all();
        $allCompanies = Company::all()->take(4);

        foreach ($allCompanies as $company) {
            for ($i = 0; $i < 20; $i++) {
                CompanyTool::create([
                    'tool_id' => $allTools->random()->id,
                    'company_id' => $company->id
                ]);
            }
        }
    }
}
