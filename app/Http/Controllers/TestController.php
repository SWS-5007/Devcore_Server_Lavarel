<?php

namespace App\Http\Controllers;

use App\Models\CompanyRole;
use App\Models\CompanyTool;
use App\Models\Industry;
use App\Models\Issue;
use App\Models\Process;
use App\Models\ProcessStage;
use App\Models\Project;
use App\Models\ProjectStage;
use App\Models\Token;
use App\Models\User;
use App\Services\Service;
use App\Services\ProjectService;
use App\Services\ReportSerivce;
use App\Services\TokenService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function index()
    {
        //$stage = ProjectStage::find(46);
        config()->set('app.company_id', 1);
        DB::enableQueryLog();
        $data = ReportSerivce::instance()->userProjectReport(['id' => 2, 'project_id' => 12]);
        return view('welcome');
        //return $projects;
        return (DB::getQueryLog());
        // $user = User::find(2);
        // $project = Project::find(12);
        // return round($user->calculateMoneyValue('MONEY', 1, 'TOTAL', Carbon::now()->addDays(-14), Carbon::now()));
        // exit();
        //$record = $stage->evaluationRecords()->where('project_evaluation_records.id', 2)->first();
        DB::enableQueryLog();
        $data = ReportSerivce::instance()->peopleReport([]);
        return $data;
        //return $record->projectStage;
        //return $stage;
        //ProjectService::instance()->initStageEvaluation($stage);
        //return $stage;
        //return $project->userPendingEvaluations($user)->get();
        //CompanyTool::find(10)->evaluationRecords;
        //$project->evaluationInstances=$project->evaluationInstances()->with(['records', 'records.project'])->get();
        //return $project->evaluationRecords;
        // $project = Issue::first();
        // $project->companyRole;
        // $project->author;
        //return $project;

        return (DB::getQueryLog());

        // $project->stages = $project->stages()->with(['ideas'])->get();

        // foreach ($project->stages as $stage) {
        //     $ideas = $stage->ideas;
        //     foreach ($ideas as $idea) {
        //         $idea->users = $idea->getUsers()->get();
        //     }
        // }


        ProjectService::instance()->nextStage($project);


        //dd(DB::getQueryLog());

        // $process->syncCompanyRoles([1, 2, 3, 4]);
        // //$process->detachCompanyRole([6, 7]);
        // $process->companyRoles;
        //$process->addCompanyRole(CompanyRole::find(6));
        return $project;
        // return view('welcome');
        // $faker =  \Faker\Factory::create();
        // $industry=new Industry();
        // $industry->name= $faker->domainWord;
        // $industry->save();
        // return $industry;
        // return Industry::whereTranslationAttribute('name', 'like', 'test')->orderByTranslationAttribute('name', 'ASC')->first();
        //return Token::with('owner')->get();
        // $token = Token::create([
        //     'model_type' => 'user',
        //     'field_name' => 'email',
        //     'field_value' => 'user20@devcore.test',
        //     'purpose' => 'reset_password',
        //     'value' => Str::random(6),
        //     'expires_at' => Carbon::now()->addHours(2),
        // ]);
        // return $token;

        // $value=Str::random(6);

        //return TokenService::instance()->checkAndUseToken('il4wOn', 'reset_password', 'user20@devcore.test', 'user', 'email');

        // TokenService::instance()->create([
        //     'model_type' => 'user',
        //     'field_name' => 'email',
        //     'field_value' => 'user20@devcore.test',
        //     'purpose' => 'reset_password',
        //     'value' => $value,
        //     'expires_at' => Carbon::now()->addHours(2),
        // ]);

        // return $value;


        // $tokens=TokenService::instance()->getTokenByParams([
        //     'purpose'=>'reset_password',
        //     'field_name'=>'email',
        //     'field_value'=>'user20@devcore.test',
        //     'model_type'=>'user',
        // ]);
        // return $tokens;
    }
}
