<?php

namespace App\Models;

use App\Services\ExperienceService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ProjectStage extends BaseModel
{
    protected static function boot()
    {
        parent::boot();
        static::saving(function(ProjectStage $model) {
         if ($model->status === 'FINISHED'){

            $users = $model->project()->first()->users()->get();
            $projectCompleteExperienceQuest = ExperienceQuest::where(
                [
                    'company_id'=>$model->project->company_id,
                    'title'=> 'Complete Project'
                ])->first();


             foreach($users as $user){

                 $xpInstance = ExperienceService::instance();
                 $increase = $xpInstance->increaseExperience(
                     [
                         "user_id" => $user->id,
                         "quest_id" => $projectCompleteExperienceQuest->id
                     ]);
            }
         }
        });
        static::deleting(function(ProjectStage $model) {
            // before delete() method call this

            //delete empty projects
            $project = $model->project()->first();
            $stageCount = $project->stages()->whereNotIn('id', [$model->id])->get()->count();
            if($stageCount < 1){
                $project->delete();
            }
        });
    }

    const STAGE_STATUS = [
        0 => 'NOT_STARTED',
        100 => 'STARTED',
        200 => 'EVALUATIONS_PENDING',
        300 => 'FINISHED',
        400 => 'TRASHED'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public $appends = ['title'];

    protected $with = ['processStage'];

    public function getTitleAttribute()
    {
        return $this->processStage->title;
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id', 'id');
    }

    public function processStage()
    {
        return $this->belongsTo(ProcessStage::class, 'stage_id', 'id');
    }

    public function evaluationInstances()
    {
        return $this->hasMany(ProjectEvaluationInstance::class, 'project_stage_id', 'id');
    }

    public function evaluationRecords()
    {
        return $this->hasManyThrough(ProjectEvaluationRecord::class, ProjectEvaluationInstance::class, 'project_stage_id', 'evaluation_instance_id', 'id', 'id');
    }

    public function issues()
    {
        $builder = $this->hasMany(Issue::class, 'project_stage_id', 'id');
        return $builder;
    }


    public function ideas()
    {
        return $this->hasMany(ProjectIdea::class, 'project_stage_id', 'id');
    }

    public function tools()
    {
        return $this->hasMany(ProjectTool::class, 'project_stage_id', 'tool_id');
    }

    public function calculateTotals()
    {
        //evaluations
        $this->total_gains = $this->evaluationRecords()->where('project_evaluation_records.status', 'COMPLETED')->where('total_value', '>', 0)->sum('total_value');
        $this->total_losses = $this->evaluationRecords()->where('project_evaluation_records.status', 'COMPLETED')->where('total_value', '<', 0)->sum('total_value');
        $this->total_evaluations = $this->evaluationRecords()->where('project_evaluation_records.status', 'COMPLETED')->count();

        //issues
        $this->total_losses += $this->issues()->sum('total_value');

        //consolidated value
        $this->consolidated_value = $this->total_gains + $this->total_losses;
    }
}
