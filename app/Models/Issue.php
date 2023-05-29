<?php

namespace App\Models;

use App\Lib\Models\HasFileTrait;
use App\Lib\Models\HasPropertiesColumnTrait;
use App\Lib\Models\Resources\HasResourcesTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Issue extends BaseModel
{
    use SoftDeletes,HasResourcesTrait, HasUserTrait, HasPropertiesColumnTrait, HasCompanyTrait;

    protected static function boot()
    {
        parent::boot();
        static::deleting(function(Issue $model) {
            // before delete() method call this
            $model->comments()->delete();
            $model->feedbacks()->delete();
        });
    }

    const TYPES = [
        'ISSUE',
        'IMPROVEMENT',
    ];

    const UNITS = [
        'MONEY',
        'TIME',
    ];
    const DIMENSIONS = [
        'WEEK',
        'MONTH',
        'TOTAL',
    ];

    protected $userColumnName = 'author_id';
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->user();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function resourcesOwnerId()
    {
        return $this->getKey();
    }

    public function getResourcesSection($type = null)
    {
        return 'issues';
    }
    public function effect()
    {
        return $this->hasOne(IssueEffect::class, 'issue_active_id', 'id');
    }

//    public function reply()
//    {
//        return $this->hasOne(IdeaIssueReply::class, 'issue_id', 'id');
//    }

    public function comments()
    {
        return $this->hasMany(IdeaIssueReply::class, 'issue_id', 'id')->where(["status" => IdeaIssueReply::TYPES[1],"type" => "ISSUE"]);
    }

    public function feedbacks()
    {
        return $this->hasMany(IdeaIssueReply::class, 'issue_id', 'id')->where(["status" => IdeaIssueReply::TYPES[0],"type" => "ISSUE"]);
    }

    public function files()
    {
        return $this->resources();
    }

    public function parent()
    {
        return $this->morphTo('parent', 'parent_type', 'parent_id', 'id');
    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function companyRole()
    {
        return $this->hasOneThrough(CompanyRole::class, User::class, 'id', 'id', 'author_id', 'company_role_id');
    }

    public function projectStage()
    {
        return $this->belongsTo(ProjectStage::class, 'project_stage_id', 'id');
    }



    public function getStage()
    {
        switch ($this->parent_type) {
            case 'process_stage':
                return $this->parent;
            case 'process_operation':
                return $this->parent->stage;
            case 'process_phase':
                return $this->parent->operation->stage;
        }
    }

    public function calculateTotals()
    {
        $dateTo = $this->projectStage->closed_at;
        if (!$dateTo) {
            $dateTo = Carbon::now();
        }
        $dateFrom = $this->projectStage->started_at;

        if ($dateFrom) {
            $this->money_total = round($this->author->calculateMoneyValue('MONEY', $this->money_value, $this->money_unit, $dateFrom, $dateTo));
            $this->time_total = round($this->author->calculateMoneyValue('TIME', $this->time_value, $this->time_unit, $dateTo, $dateTo));
            $this->total_value = $this->money_total + $this->time_total;
        }
    }
}
