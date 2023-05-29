<?php

use App\Models\Company;
use App\Models\ProjectIdea;
use App\Models\Project;
use App\Models\ProjectEvaluationInstance;
use App\Models\ProjectEvaluationRecord;
use App\Models\ProjectStage;
use App\Models\ProjectTool;
use App\Models\ProjectUser;
use App\Services\ProjectService;
use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      #  Project::truncate();
      #  ProjectIdea::truncate();
        #ProjectTool::truncate();
      #  ProjectStage::truncate();
       # ProjectUser::truncate();

     #   ProjectEvaluationRecord::truncate();
     #   ProjectEvaluationInstance::truncate();

        $faker =  \Faker\Factory::create();

        $companies = Company::all()->take(1);

        $globalCount = 1;
        foreach ($companies as $company) {
            $processes = $company->processes;
            $users = $company->users;
            if ($processes->count() > 1) {
                $count = random_int(min($processes->count(), 5), ($processes->count()));

                for ($i = 0; $i < $count; $i++) {
                    $process = $processes[$i];

                    //get ideas
                    $ideas = $process->ideas()->canBeOnProject()->get();

                    //get tools
                    $tools = $company->companyTools->random(1);

                    //tool ideas
//                    $toolsIdeas = $tools->map(function ($element) {
//                        return $element->ideas()->canBeOnProject()->get()->map(function ($e) {
//                            $e->id;
//                        });
//                    });

                    $projectData = [
                        'process_id' => $process->id,
                        'company_id' => $company->id,
                        'name' => $faker->words(2, true) . " ($globalCount)",
                        #'type' => $faker->randomElement(Project::PROJECT_TYPES),
                        'evaluation_type' => $faker->randomElement(Project::EVALUATION_TYPES),
                        'evaluation_interval_unit' => $faker->randomElement(Project::EVALUATION_INTERVAL_UNITS),
                        'evaluation_interval_amount' => 1,
                        'type' => $faker->randomElement(Project::PROJECT_TYPES),
                        'budget' => strval(random_int(300000, 100000000)),
                        'user_ids' => $users->random(2)->map(function ($e) {
                            return $e->id;
                        })->values()->toArray(),
                        'idea_ids' => $ideas->random(random_int(4, $ideas->count()))->map(function ($e) {
                            return $e->id;
                        })->values()->toArray(),
                        'company_tool_ids' => []
//                        'company_tool_ids' => $tools->map(function ($e) {
//                            return $e->id;
//                        })->values()->toArray()
                    ];
                    try {

                        $project = ProjectService::instance()->create($projectData);
                        ProjectService::instance()->nextStage($project);


                        foreach ($project->evaluationRecords as $record) {
                            ProjectService::instance()->evaluateIdea([
                                'time_unit' => 'TOTAL',
                                'time_value' => 0,
                                'money_unit' => 'TOTAL',
                                'money_value' => $faker->randomElement([1, -1]) * random_int(10000, 100000),
                                'skipped' => false,
                            ], $record);
                        }

                        ProjectService::instance()->nextStage($project);
                        ProjectService::instance()->completeStage($project->stages[0]);


                        $globalCount++;
                    } catch (\Illuminate\Validation\ValidationException $ex) {
                        print_r($projectData);
                        print_r($ex->errors());
                        exit();
                    }
                }
            }
        }
    }
}
