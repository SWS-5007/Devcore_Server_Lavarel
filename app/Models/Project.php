<?php

namespace App\Models;

use App\Lib\Context;
use App\Lib\Models\HasPropertiesColumnTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Project extends BaseModel
{
    use HasCompanyTrait, HasPropertiesColumnTrait, HasUserTrait;

    const PROJECT_TYPES = [
        'NORMAL',
        'ON_GOING'
    ];

    const EVALUATION_INTERVAL_UNITS = [
        'DAYS',
        'WEEKS',
        'MONTHS',
        'YEARS',
    ];

    const EVALUATION_TYPES = [
        'STAGE_FINISH',
        'PERIODIC',
    ];

    const PROJECT_STATUS = [
        0 => 'NOT_STARTED',
        100 => 'STARTED',
        200 => 'EVALUATIONS_PENDING',
        300 => 'FINISHED',
        400 => 'TRASHED'
    ];

    protected $primaryKey = 'id';
    protected $userColumnName = 'author_id';
    protected $casts = [
        'issue_evaluation_roles' => 'array',
        'issue_template_roles' => 'array',
        'share_access_roles' => 'array'
    ];


    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function stages()
    {
        return $this->hasMany(ProjectStage::class, 'project_id', 'id');
    }

    public function getProjectStageByProcessStage($stageId)
    {
        return $this->stages()->where('stage_id', $stageId)->first();
    }

    public function author()
    {
        return $this->user();
    }

    public function projectUsers()
    {
        return $this->hasMany(ProjectUser::class, 'project_id', 'id');
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, ProjectUser::class, 'project_id', 'id', 'id', 'user_id');
    }

    public function ideas($user = null)
    {
        if (!$user) {
            $user = Context::get()->user;
        }

        if (!$user) {
            return collect([]);
        }

        $builder = $this->hasMany(ProjectIdea::class, 'project_id', 'id');

        return $builder;
    }

    public function issues()
    {
        $builder = $this->hasMany(Issue::class, 'project_id', 'id');
        return $builder;
    }

    public function userIdeas($user = null)
    {

        if (!$user) {
            $user = Context::get()->user;
        }

        if (!$user) {
            return collect([]);
        }

        $builder = $this->ideas($user)->whereHas('idea', function ($query) use ($user) {
            $query
                //->whereNull("company_role_ids")->orWhereJsonContains("company_role_ids", (string) $user->company_role_id)
                ->whereHasMorph('parent', [ProcessStage::class, ProcessOperation::class, ProcessPhase::class], function ($q2) use ($user) {
                    $q2->whereHas('companyRoles', function ($q3) use ($user) {
                        $q3->where('company_role_id', $user->company_role_id);
                    });
               });
        });

        return $builder;
    }


    public function companyTools()
    {
        return $this->hasManyThrough(CompanyTool::class, ProjectTool::class, 'project_id', 'id', 'id', 'company_tool_id');
    }

    public function tools()
    {
        $builder = $this->hasMany(ProjectTool::class, 'project_id', 'id');
        return $builder;
    }

    public function evaluationInstances()
    {
        return $this->hasMany(ProjectEvaluationInstance::class, 'project_id', 'id')->with('records');
    }

    public function evaluationRecords()
    {
        return $this->hasManyThrough(ProjectEvaluationRecord::class, ProjectEvaluationInstance::class, 'project_id', 'evaluation_instance_id', 'id', 'id');
    }

    public function pendingEvaluations($user = null)
    {
        $evaluations = $this->evaluationInstances()->with(['records' => function ($q) use ($user) {
            if ($user) {
                $q->where('author_id', $user->id);
                $q->where('status', 'PENDING');
            }
        }])->whereHas('records', function ($q) use ($user) {
            if ($user) {
                $q->where('author_id', $user->id);
                $q->where('status', 'PENDING');
            }
        });

        return $evaluations;

    }

    public function userPendingEvaluations($user = null)
    {
        if (!$user) {
            $user = Context::get()->user;
        }

        if (!$user) {
            return collect([]);
        }
        $evaluations = $this->pendingEvaluations($user);
        return $evaluations;
    }

    public function calculateNextEvaluationDate($current = null)
    {
        if (!$current) {
            $current = Carbon::now();
        }

        if ($this->evaluation_type === 'PERIODIC') {
            switch ($this->evaluation_interval_unit) {
                case 'DAYS':
                    return $current->addDays($this->evaluation_interval_amount)->startOfDay();
                case 'WEEKS':
                    return $current->addWeeks($this->evaluation_interval_amount)->startOfDay();
                case 'MONTHS':
                    return $current->addMonths($this->evaluation_interval_amount)->startOfDay();
                case 'YEARS':
                    return $current->addYears($this->evaluation_interval_amount)->startOfDay();
            }
        }

        return null;
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
