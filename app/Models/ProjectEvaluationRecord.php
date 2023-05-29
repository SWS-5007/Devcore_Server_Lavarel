<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectEvaluationRecord extends Model
{
    // const UNITS = [
    //     'MONEY',
    //     'TIME',
    // ];
    const DIMENSIONS = [
        'WEEK',
        'MONTH',
        'TOTAL',
    ];
    const STATUS = [
        'PENDING',
        'COMPLETED',
        'SKIPPED',
        'MISSING'
    ];

    public function idea()
    {
        return $this->belongsTo(Idea::class, 'idea_id', 'id');
    }

    public function projectIdea()
    {
        return $this->belongsTo(ProjectIdea::class, 'project_idea_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
        //return $this->hasOneThrough(Project::class, ProjectEvaluationInstance::class, 'id', 'id', 'evaluation_instance_id', 'project_id');
    }

    public function projectStage()
    {
        return $this->belongsTo(ProjectStage::class, 'project_stage_id', 'id');
        //return $this->hasOneThrough(ProjectStage::class, ProjectEvaluationInstance::class, 'id', 'id', 'evaluation_instance_id', 'project_stage_id');
    }

    public function evaluationInstance()
    {
        return $this->belongsTo(ProjectEvaluationInstance::class, 'evaluation_instance_id', 'id');
    }

    public function calculateTotals()
    {
        $this->money_total = round($this->author->calculateMoneyValue('MONEY', $this->money_value, $this->money_unit, $this->evaluationInstance->evaluation_period_start, $this->evaluationInstance->evaluation_period_end));
        $this->time_total = round($this->author->calculateMoneyValue('TIME', $this->time_value, $this->time_unit, $this->evaluationInstance->evaluation_period_start, $this->evaluationInstance->evaluation_period_end));

        $this->total_value = $this->money_total + $this->time_total;
    }
}
