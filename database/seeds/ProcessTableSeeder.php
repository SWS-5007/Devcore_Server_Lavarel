<?php

use App\Models\Company;
use App\Models\ModelHasCompanyRole;
use App\Models\Process;
use App\Models\ProcessOperation;
use App\Models\ProcessPhase;
use App\Models\ProcessStage;
use Illuminate\Database\Seeder;

class ProcessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Process::truncate();
//        ProcessOperation::truncate();
//        ProcessStage::truncate();
//        ProcessPhase::truncate();
//        ModelHasCompanyRole::where('model_type', 'process')->delete();
//        ModelHasCompanyRole::where('model_type', 'process_stage')->delete();
//        ModelHasCompanyRole::where('model_type', 'process_operation')->delete();
//        ModelHasCompanyRole::where('model_type', 'process_phase')->delete();

        $faker =  \Faker\Factory::create();
        $companies = Company::all()->take(4);
        $globalIndex = 1;
        foreach ($companies as $company) {
            $companyRoles = $company->companyRoles;
            $authors = $company->users()->get()->filter(function ($user) {
                return $user->hasRole('Company Admin') || $user->hasRole('Company Manager');
            })->all();
            $max = random_int(5, 11);
            for ($i = 1; $i < $max; $i++) {
                $process = Process::create([
                    'title' => ucwords($faker->words(2, true) . " ($i)"),
                    'company_id' => $company->id,
                    'author_id' => $faker->randomElement($authors)->id,
                ]);

                #$assignableCompanyRoles = $companyRoles->random(random_int(0, $companyRoles->count()));
                foreach ($companyRoles as $companyRole) {
                    ModelHasCompanyRole::create([
                        'model_type' => 'process',
                        'model_id' => $process->id,
                        'company_role_id' => $companyRole->id,
                    ]);
                }

                //stages
                $maxStages = random_int(5, 11);
                for ($stageIndex = 1; $stageIndex < $maxStages; $stageIndex++) {
                    $stage = ProcessStage::create([
                        'title' => ucwords($faker->words(2, true) . " ($i)"),
                        'company_id' => $company->id,
                        'process_id' => $process->id,
                        'description' => $faker->text(200),
                        'author_id' => $faker->randomElement($authors)->id
                    ]);

                   # $assignableCompanyRoles = $companyRoles->random(random_int(0, $companyRoles->count()));
                    foreach ($companyRoles as $companyRole) {
                        ModelHasCompanyRole::create([
                            'model_type' => 'process_stage',
                            'model_id' => $stage->id,
                            'company_role_id' => $companyRole->id,
                        ]);
                    }

                    //operations
                    $maxOperations = random_int(1, 6);
                    for ($opId = 1; $opId < $maxOperations; $opId++) {
                        $operation = ProcessOperation::create([
                            'title' => ucwords($faker->words(2, true) . " ($i)"),
                            'process_id' => $process->id,
                            'company_id' => $company->id,
                            'stage_id' => $stage->id,
                            'description' => $faker->text(200),
                            'author_id' => $faker->randomElement($authors)->id
                        ]);
                        #$assignableCompanyRoles = $companyRoles->random(random_int(0, $companyRoles->count()));
                        foreach ($companyRoles as $companyRole) {
                            ModelHasCompanyRole::create([
                                'model_type' => 'process_operation',
                                'model_id' => $operation->id,
                                'company_role_id' => $companyRole->id,
                            ]);
                        }

                        //phases
                        $maxPhases = random_int(1, 6);
                        for ($phaseIndex = 1; $phaseIndex < $maxPhases; $phaseIndex++) {
                            $phase = ProcessPhase::create([
                                'title' => ucwords($faker->words(2, true) . " ($i)"),
                                'process_id' => $process->id,
                                'company_id' => $company->id,
                                'operation_id' => $operation->id,
                                'description' => $faker->text(200),
                                'author_id' => $faker->randomElement($authors)->id
                            ]);
                          #  $assignableCompanyRoles = $companyRoles->random(random_int(0, $companyRoles->count()));
                            foreach ($companyRoles as $companyRole) {
                                ModelHasCompanyRole::create([
                                    'model_type' => 'process_phase',
                                    'model_id' => $phase->id,
                                    'company_role_id' => $companyRole->id,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
