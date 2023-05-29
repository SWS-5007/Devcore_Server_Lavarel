<?php

namespace App\Models;

use App\Lib\Context;

class ProjectEvaluationInstance extends BaseModel
{

    public $casts = [
        'evaluation_period_end' => 'datetime',
        'evaluation_period_start' => 'datetime',
    ];

    const STATUS = [
        'OPEN',
        'CLOSED',
    ];

    public function records($user = null)
    {
        $records = $this->hasMany(ProjectEvaluationRecord::class, 'evaluation_instance_id', 'id');
        if ($user) {
            $records->where('author_id', $user->id);
        }
        return $records;
    }

    public function userRecords($user = null)
    {
        if (!$user) {
            $user = Context::get()->user;
        }

        if (!$user) {
            return collect([]);
        }
        $records = $this->records($user);
        return $records;
    }

    public function projectStage()
    {
        return $this->belongsTo(ProjectStage::class, 'project_stage_id', 'id');
    }

    public function calculateTotals()
    {
        $this->total_gains = $this->records()->where('project_evaluation_records.status', 'COMPLETED')->where('total_value', '>', 0)->sum('total_value');
        $this->total_losses = $this->records()->where('project_evaluation_records.status', 'COMPLETED')->where('total_value', '<', 0)->sum('total_value');
        $this->consolidated_value = $this->total_gains + $this->total_losses;
        $this->total_evaluations = $this->records()->where('project_evaluation_records.status', 'COMPLETED')->count();
    }
}
