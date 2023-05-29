<?php

namespace App\Models;

use App\Lib\Context;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ProjectIdea extends BaseModel
{
    protected $with = ['idea'];

    protected $appends = ['title'];

    public function getTitleAttribute()
    {
        return $this->idea->title;
    }

    public function idea()
    {
        return $this->belongsTo(Idea::class, 'idea_id', 'id');
    }

    public function scopeWithIdeaPermission($query, $type, $user = null)
    {
        if (!$user) {
            $user = Context::get()->user;
        }

        if (!$user) {
            return collect([]);
        }
        return $query->whereHas('idea' , function($q) use($user)  {
            return $q;
        });
    }

    public function stage()
    {
        return $this->belongsTo(ProjectStage::class, 'project_stage_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function evaluations(){
        return $this->hasMany(ProjectEvaluationRecord::class, 'project_idea_id', 'id');
    }

    public function users()
    {
        $projectId = $this->project->users->pluck(['id']);
        $builder = $this->idea->users()->whereIn('users.id',$projectId);
        return $builder;
    }

    public function calculateTotals(){
        $this->total_gains = $this->evaluations()->where('project_evaluation_records.status', 'COMPLETED')->where('total_value', '>', 0)->sum('total_value');
        $this->total_losses = $this->evaluations()->where('project_evaluation_records.status', 'COMPLETED')->where('total_value', '<', 0)->sum('total_value');
        $this->consolidated_value = $this->total_gains + $this->total_losses;
        $this->total_evaluations = $this->evaluations()->where('project_evaluation_records.status', 'COMPLETED')->count();
    }
}
