<?php

namespace App\Services;

use App\Lib\Models\Notification;
use App\Models\CompanyTool;
use App\Models\Idea;
use App\Models\Project;
use App\Models\ProjectEvaluationInstance;
use App\Models\ProjectEvaluationRecord;
use App\Models\ProjectIdea;
use App\Models\ProjectStage;
use App\Models\ProjectTool;
use App\Models\ProjectUser;
use App\Models\User;
use App\Models\ShareLink;
use App\Validators\ProjectEvaluationRecordValidator;
use App\Validators\ProjectValidator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Nuwave\Lighthouse\Execution\Utils\Subscription;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(Project::class, false);
    }


    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    protected function syncUsers($data, $instance, $option)
    {
        $input = collect($data);

        if ($input->get('user_ids')) {
            $instanceUsers = $instance->projectUsers();
            $instanceUsersWhereNotIn = $instanceUsers->whereNotIn('user_id', $input->get('user_ids'));
            $instanceUsersWhereNotIn->delete();

            $validUsers = User::whereIn('id', $input->get('user_ids'))->get();
            $relations = [];
            foreach ($validUsers->values() as $user) {
                $userRelation = [
                    'company_id' => $instance->company_id,
                    'user_id' => $user->id,
                    'project_id' => $instance->id,
                    'created_at' => Carbon::now()
                ];

                $relations[] = $userRelation;
            }

            ProjectUser::insertOrIgnore($relations);
            $instance->load('users');
        }
    }

    protected function syncTools($data, $instance,$option)
    {
        $input = collect($data);

        $instanceTools = $instance->tools();
        $companyToolIds = $input->get('company_tool_ids');
        $stageId = $input->get('stage_id');
        if(!$companyToolIds){
            $instanceTools->whereIn('project_stage_id', $stageId)->where(['project_id' => $instance->id])->delete();
        }

        if ($companyToolIds) {

            $validTools = CompanyTool::whereIn('id', $companyToolIds)->get();
            if ($validTools->isEmpty()) {
                throw new BadRequestHttpException();
            }
            $relations = [];

            if($option === "update") {
                $instanceToolsInStages = $instanceTools->whereIn('project_stage_id', $stageId);
                $instanceToolsWhereNotIn = $instanceToolsInStages->whereNotIn('company_tool_id', $companyToolIds);
                $instanceToolsWhereNotIn->delete();

                $allStages = ProjectStage::where(['project_id' => $instance->id])->orderBy('d_order')->get();
                $toolStages = $input->get('stage_id');

                foreach($toolStages as $key=>$toolStageId) {
                    foreach ($validTools->values() as $companyTool) {

                        $toolRelation = [
                            'company_id' => $instance->company_id,
                            'company_tool_id' => $companyTool->id,
                            'project_stage_id' => $allStages[$key]->id,
                            'tool_id' => $companyTool->tool_id,
                            'project_id' => $instance->id,
                        ];

                        $exists = ProjectTool::where($toolRelation)->first();
                        if (!$exists) {
                            $toolRelation['updated_at'] = Carbon::now();
                            $relations[] = $toolRelation;
                        }

                    }
                }
            } else {

                $allStages = ProjectStage::where(['project_id' => $instance->id])->orderBy('d_order')->get();
                $toolStages = $input->get('stage_id');

                foreach($toolStages as $key=>$toolStageId) {
                    foreach ($validTools->values() as $companyTool) {
                        $toolRelation = [
                            'company_id' => $instance->company_id,
                            'company_tool_id' => $companyTool->id,
                            'tool_id' => $companyTool->tool_id,
                            'project_stage_id' => $allStages[$key]->id,
                            'project_id' => $instance->id,
                            'created_at' => Carbon::now()
                        ];
                        $relations[] = $toolRelation;
                    }
                }

            }
            ProjectTool::insert($relations);
            $instance->load('companyTools');
        }
    }

    protected function syncIdeas($data, $instance,$option)
    {

        $input = collect($data);

        $instanceIdeas = $instance->ideas();
        $ideaIds = $input->get('idea_ids');
        $stageId = $input->get('stage_id');

        if(!$ideaIds){
            $instanceIdeas->whereIn('project_stage_id', $stageId)->where(['project_id' => $instance->id])->delete();
        }
        if ($ideaIds) {

            $validIdeas = Idea::whereIn('id', $ideaIds)->get();
            $relations = [];

            if($option === 'update'){


                ProjectIdea::whereIn('project_stage_id', $stageId)
                    ->where(['project_id' => $instance->id])
                    ->whereNotIn('idea_id', $ideaIds)->delete();


                foreach ($validIdeas->values() as $idea) {
                   $stageId = $instance->stages->where('stage_id',  $idea->getStage()->id)->first()->id;

                    $ideaRelation = [
                        'company_id' => $instance->company_id,
                        'idea_id' => $idea->id,
                        'process_id' => $idea->process_id,
                        'project_stage_id' => $stageId,
                        'process_stage_id' => $idea->getStage()->id,
                        'project_id' => $instance->id,
                    ];

                    $exists = ProjectIdea::where($ideaRelation)->first();
                    if (!$exists) {
                        $ideaRelation['updated_at'] = Carbon::now();
                        $relations[] = $ideaRelation;
                    }
                }

            } else {
                foreach ($validIdeas->values() as $idea) {
                   $processStage = $idea->getStage();
                   if(!$processStage) return;
                   $processStageId = $processStage->id;
                   $stageId = $instance->stages->where('stage_id', $processStageId )->first()->id;

                    $ideaRelation = [
                        'company_id' => $instance->company_id,
                        'idea_id' => $idea->id,
                        'process_id' => $idea->process_id,
                        'project_stage_id' => $stageId,
                       // 'project_stage_id' => $allStages[$key]->id,
                        'process_stage_id' => $processStageId,
                        'project_id' => $instance->id,
                    ];

                    $exists = ProjectIdea::where($ideaRelation)->first();
                    if (!$exists) {
                        $ideaRelation['created_at'] = Carbon::now();
                        $relations[] = $ideaRelation;
                    }
                }
            }

            ProjectIdea::insert($relations);

            $instance->load('ideas');
        }
    }

    protected function syncStages($data, $object, $option)
    {
        $input = collect($data);

        if($option === "create") {
            $process = $object->process;

            $allStages = $process->stages()->orderBy('d_order')->get();
            foreach ($allStages as $stage) {
                $process = $object->process;
                //create the next stage of the project
                $projectStage = new ProjectStage();
                $projectStage->status = 'NOT_STARTED';
                $projectStage->project_id = $object->id;
                $projectStage->process_id = $process->id;
                $projectStage->stage_id = $stage->id;
                $projectStage->d_order = $stage->d_order;
                $projectStage->save();
            }
        }
    }

    protected function fillFromArray($option, $data, $instance)
    {
        $data = collect($data);
        if (!config("app.company_id")) {
            $instance->company_id = $data->get('company_id', $instance->company_id);
        }
        $instance->name = $data->get('name', $instance->name);
        $instance->budget = $data->get('budget', $instance->budget);
        $instance->evaluation_type = $data->get('evaluation_type', $instance->evaluation_type);
        $instance->evaluation_interval_amount = $data->get('evaluation_interval_amount', $instance->evaluation_interval_amount);
        $instance->evaluation_interval_unit = $data->get('evaluation_interval_unit', $instance->evaluation_interval_unit);

        //Advanced settings
        if($data->get('use_advanced')){
            $instance->use_advanced = $data->get('use_advanced', $instance->use_advanced);
        } else {
            $instance->use_advanced = false;
        }

        if($data->get('issue_evaluation_roles')){
            $instance->issue_evaluation_roles = $data->get('issue_evaluation_roles', $instance->issue_evaluation_roles);
        } else {
            $instance->issue_evaluation_roles = null;
        }

        if($data->get('issue_template_roles')){
            $instance->issue_template_roles = $data->get('issue_template_roles', $instance->issue_template_roles);
        } else {
            $instance->issue_template_roles = null;
        }

        if($data->get('share_access_roles')){
            $instance->share_access_roles = $data->get('share_access_roles', $instance->share_access_roles);
        } else {
            $instance->share_access_roles = null;
        }

        if ($option === 'create') {
            $instance->status = 'STARTED';
            $instance->started_at = Carbon::now();
            $instance->process_id = $data->get('process_id', $instance->process_id);
            $instance->type = $data->get('type', $instance->type);
        } else {
            $instance->updated_at = Carbon::now();
            $instance->type = $data->get('type', $instance->type);
        }
        return $instance;
    }



    protected function getValidator($data, $object, $option)
    {
        return new ProjectValidator($data, $object, $option);
    }

    protected function created($data, $object)
    {
        $this->syncUsers($data, $object, 'create');
        $this->syncStages($data, $object, 'create');
        $this->syncTools($data, $object, 'create');
        $this->syncIdeas($data, $object, 'create');

        //start the project
        if ($object->type === 'NORMAL') {
            $this->nextStage($object);
        } else {
            $this->initOnGoingProject($object);
        }
    }

    protected function updated($data, $object)
    {
        $this->syncIdeas($data, $object, 'update');
        $this->syncUsers($data, $object, 'update');
        $this->syncTools($data, $object, 'update');
        $this->syncStages($data, $object, 'update');
    }

    protected function deleted($object)
    {
        return $object;
    }

    public function initStageEvaluation($stage)
    {
        DB::transaction(function () use ($stage) {
            $project = $stage->project;

            if (!$stage->started_at) {

                throw new BadRequestHttpException('Invalid stage status!');
            }

            $lastEvaluationInstance = $stage->evaluationInstances()->orderBy('started_at', 'DESC')->first();

            if ($lastEvaluationInstance) {
                $lastEvaluationPeriodEnd = $lastEvaluationInstance->evaluation_period_end->addSeconds(1);
            } else {
                $lastEvaluationPeriodEnd = $stage->started_at->startOfDay();
            }

            //save the stage status
            $stage->status = 'EVALUATIONS_PENDING';
            $ideasEmpty = $stage->ideas->isEmpty();
            if ($ideasEmpty) {
                $stage->status = 'FINISHED';
                $stage->closed_at = Carbon::now();
            }
            $stage->save();

            //close the evaluations on the same stage
            $evaluationInstances = $stage->evaluationInstances()->where('status', 'OPEN')->get();
            foreach ($evaluationInstances as $instance) {
                $instance->status = 'CLOSED';
                $instance->save();
                //put the pending records as missing
                $instance->records()->where('status', 'PENDING')->update([
                    'status' => 'MISSING',
                    'updated_at' => Carbon::now()
                ]);
            }

            //if the stage it's finished
            if ($stage->status == 'FINISHED') {
                return null;
            }

            //create a evaluation instance
            $evaluation = new ProjectEvaluationInstance();
            $evaluation->company_id = $project->company_id;
            $evaluation->process_id = $project->process_id;
            $evaluation->project_id = $project->id;
            $evaluation->project_stage_id = $stage->id;
            $evaluation->evaluation_period_start = $lastEvaluationPeriodEnd;
            $evaluation->evaluation_period_end = Carbon::now()->endOfDay();
            $evaluation->status = 'OPEN';
            $evaluation->started_at = Carbon::now();
            $evaluation->save();

            //save the next scheduler date
            $nextScheduleProcess = $project->calculateNextEvaluationDate(Carbon::now());
            if ($nextScheduleProcess) {
                $stage->next_schedule_process = $nextScheduleProcess;
                $stage->save();
            }

            //get ideas of the stage

            foreach ($stage->ideas as $idea) {
                //get the users of the idea
                $users = $idea->users;
                $projectUsers = $project->users;

                $records = [];
                foreach ($users as $user) {

                    $projectUser = $projectUsers->where('id', $user->id)->first();
                    $userCompanyRoleId = $user->company_role_id;
                    $ideaCompanyRoleIds = $idea->idea->company_role_ids ? $idea->idea->company_role_ids : [];
                    if(in_array($userCompanyRoleId, $ideaCompanyRoleIds) && $projectUser){
                        $records[] = [
                            'company_id' => $evaluation->company_id,
                            'process_id' => $evaluation->process_id,
                            'project_id' => $evaluation->project_id,
                            'project_stage_id' => $evaluation->project_stage_id,
                            'process_stage_id' => $stage->stage_id,
                            'project_idea_id' => $idea->id,
                            'project_user_id' => $projectUser->id,
                            'author_id' => $user->id,
                            'idea_id' => $idea->idea_id,
                            'evaluation_instance_id' => $evaluation->id,
                            'status' => 'PENDING',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                    }
                }

                ProjectEvaluationRecord::insert($records);

                //there where no users to evaluate the ideas
              //  Log::info("records::: ", [json_encode($records)]);
                if (!count($projectUsers)) {
                    $evaluation->status = 'CLOSED';
                    $evaluation->closed_at = Carbon::now();
                    $evaluation->save();
                    $stage->status = 'FINISHED';
                    $stage->closed_at = Carbon::now();
                    $stage->save();
                }
            }
        });
    }

    public function completeStage($stage)
    {
        DB::transaction(function () use ($stage) {
            if ($stage->status === 'EVALUATIONS_PENDING') {
                $stage->status = 'FINISHED';
                $stage->closed_at = Carbon::now();
                $stage->save();
                $stage->evaluationInstances()->where('status', 'OPEN')->update([
                    'closed_at' => Carbon::now(),
                    'status' => 'CLOSED'
                ]);
            } elseif ($stage->status === 'STARTED') {
                $this->initStageEvaluation($stage);
            }

            if (!$stage->project->stages()->where('status', '!=', 'FINISHED')->count()) {
                $stage->project->status = 'FINISHED';
                $stage->project->finished_at = Carbon::now();
                $stage->project->save();
            }

            // Delete ShareLinks
            ShareLink::where("project_id", $stage->project->id)->delete();
        });
        return $stage->project;
    }

    public function initOngoingProject($project)
    {
        foreach ($project->stages as $stage) {
            $this->initProjectStage($project, $stage);
        }
    }

    protected function initProjectStage($project, $stage)
    {
        //save the next scheduler date
        $nextScheduleProcess = $project->calculateNextEvaluationDate(Carbon::now());

        if ($nextScheduleProcess) {
            $stage->next_schedule_process = $nextScheduleProcess;
        }
        $stage->started_at = Carbon::now();
        $stage->status = "STARTED";
        $stage->save();
    }


    public function nextStage($project)
    {
        DB::transaction(function () use ($project) {
            $allStages = $project->stages()->orderBy('d_order')->get();
            if ($project->status === 'STARTED') {
                if ($project->type === 'NORMAL') {
                    $currentProjectStage = $project->stages()->where('status', 'STARTED')->orderBy('d_order', 'desc')->first();
                    $currentStageEvaluation = $project->stages()->where('status', 'EVALUATIONS_PENDING')->orderBy('d_order', 'desc')->first();

                    $nextStage = null;
                    if ($currentProjectStage) {
                        $nextStage = $allStages->where('d_order', '>', $currentProjectStage->d_order)->first();
                        //save the current stage status

                      $this->initStageEvaluation($currentProjectStage);

                    } elseif($currentStageEvaluation) {
                        //  Project type normal Evaluation PERIODIC
                        $nextStage = $allStages->where('d_order', '>', $currentStageEvaluation->d_order)->first();
                        $this->completeStage($currentStageEvaluation);
                    }
                    else {
                        $nextStage = $allStages->first();
                    }

                    if ($nextStage) {
                        $this->initProjectStage($project, $nextStage);
                    } else {
                        $project->status = 'FINISHED';
                        $project->finished_at = Carbon::now();
                    }
                }
            }
        });
        return $project;
    }

    public function listMyProjects($filter)
    {
        return $this->getBaseQuery()->whereHas('users', function ($q) {
            $q->where('user_id', $this->getUser()->id);
        });
    }

    public function evaluateIdea($input, $record = null)
    {
        return DB::transaction(function () use ($input, $record) {
            $data = collect($input);

            //validate
            $validator = new ProjectEvaluationRecordValidator($input, $record);
            $validator->execute();
            //get the evaluation period
            $evaluationInstance = $record->evaluationInstance;

            if (!$evaluationInstance) {
                throw new NotFoundHttpException();
            }

            if ($data->get('skipped', false)) {
                if ($record->status === "PENDING") {
                    $record->status = "SKIPPED";
                }
                //save the record
                $record->save();
            } else {
                $record->status = "COMPLETED";
                $record->money_value = $data->get('money_value', $record->money_value ? $record->money_value : 0);
                $record->money_unit = "TOTAL"; //money only total value
                $record->time_value = $data->get('time_value', $record->time_value ? $record->time_value : 0);
                $record->time_unit = $data->get('time_unit', $record->time_unit);
                $record->author_yearly_costs = $record->author->yearly_costs;
                $record->completed_at = Carbon::now();

                //calculate the money value
                $record->calculateTotals();

                //save the record
                $record->save();
            }


            //save the total amount of complete evaluations
            $evaluationInstance->calculateTotals();
            $evaluationInstance->save();


            //save values to project stage
            $record->projectStage->calculateTotals();
            $record->projectStage->save();


            //save values to project idea
            $record->projectIdea->calculateTotals();
            $record->projectIdea->save();


            //save values to idea
            $record->idea->calculateTotals();
            $record->idea->save();


            //save values to project
            $record->project->calculateTotals();
            $record->project->save();


            //save values to user
            $record->author->calculateTotals();
            $record->author->save();

            if ($record->idea->company_tool_id) {
                //save values to company tool
                $record->idea->companyTool->calculateTotals();
                $record->idea->companyTool->save();
            }

            return $record;
        });
    }

    public function addUser($projectId, $userId)
    {
        $project = $this->findByPrimaryKey($projectId);
        
        $userRelation = [
            'company_id' => $project->company_id,
            'user_id' => $userId,
            'project_id' => $projectId,
            'created_at' => Carbon::now()
        ];
        $relations[] = $userRelation;
        ProjectUser::insertOrIgnore($relations);

        return $project;
    }
}
