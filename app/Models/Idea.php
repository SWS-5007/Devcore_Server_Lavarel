<?php

namespace App\Models;

use App\Lib\Context;
use App\Lib\Models\HasDisplayOrderFieldTrait;
use App\Lib\Models\HasFileTrait;
use App\Lib\Models\HasPropertiesColumnTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Lib\Models\Resources\HasResourcesTrait;
use App\Lib\Models\Resources\HasResourceCollectionTrait;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Log;
use Mpociot\Versionable\VersionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Idea extends BaseModel
{

    use SoftDeletes, HasResourcesTrait, HasResourceCollectionTrait, HasUserTrait, HasPropertiesColumnTrait, HasCompanyTrait,
        HasCompanyToolsTrait, HasCompanyRolesTrait/*,  , VersionableTrait*/;

    protected static function boot()
    {
        parent::boot();
        static::deleting(function(Idea $model) {
            // before delete() method call this
            $model->projectIdeas()->delete();
            $model->improvements()->delete();
            $model->problems()->delete();
            $model->feedback()->delete();
            $model->ideaContent()->delete();
        });
    }
    const IDEA_TYPES = [
        'PROCESS',
        'TOOL'
    ];

    const IDEA_STATUS = [
        0 => 'NEW',
        100 => 'TESTING',
        //200 => 'REVIEW',
        300 => 'ADOPTED',
        400 => 'ARCHIVED',
    ];


    protected $userColumnName = 'author_id';
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'IdeaContent.markup' => 'array',
        'company_role_ids' => 'array',
        'company_tool_ids' => 'array'
    ];

    public function author(): BelongsTo
    {
        return $this->user();
    }

    public function resourcesCollectionSection() {
        return $this->uuid;
    }

    public function resourcesOwnerId()
    {
        return $this->getKey();
    }

//    public function getFileOwnerId()
//    {
//        Log::info("GETTING FILE OWNER ID : !");
//        return $this->id;
//    }

    public function getResourcesSection($type = null)
    {
      //  Log::info("GET RESOURCE SECTION ! ");
        return 'ideas';
    }

    public function files()
    {
        return $this->resources();
    }

    public function scopeCanBeOnProject($builder)
    {
        return $builder->whereIn('status', ['ADOPTED', 'TESTING']);
    }

    public function source()
    {
        return $this->morphTo('source', 'source_type', 'source_id', 'id');
    }

    public function feedback()
    {
        return $this->hasOne(IdeaIssueReply::class, 'idea_id', 'id')->where(["status" => IdeaIssueReply::TYPES[0], "type" => "IDEA"]);
    }

    public function parent()
    {
        return $this->morphTo('parent', 'parent_type', 'parent_id', 'id');
    }

    public function companyRoles()
    {
        return $this->hasManyThrough(CompanyRole::class, ModelHasCompanyRole::class, 'model_id', 'id', 'id', 'company_role_id')->where('model_type', array_search(static::class, Relation::morphMap()) ?: static::class);
    }


    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id', 'id');
    }

    public function companyTool()
    {
        return $this->belongsTo(CompanyTool::class, 'company_tool_id', 'id');
    }

    public function ideaContent()
    {
        return $this->hasMany(IdeaContent::class, 'idea_id', 'id');
    }

    public function tool()
    {
        return $this->hasOneThrough(Tool::class, CompanyTool::class, 'id', 'id', 'company_tool_id', 'tool_id');
    }

    public function companyTools()
    {
        return $this->hasManyThrough(CompanyTool::class, ModelHasCompanyTool::class, 'model_id', 'id', 'id', 'company_tool_id')->where('model_type', 'idea');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function projectIdeas()
    {
        return $this->hasMany(ProjectIdea::class, 'idea_id', 'id');
    }

    public function users()
    {
        return $this->parent->users();
    }

    public function comments()
    {
        return $this->hasMany(IdeaIssueReply::class, 'issue_id', 'id')->where(["status" => IdeaIssueReply::TYPES[1], "type" => "IDEA"]);
    }

    public function idea_issues()
    {
        return $this->hasMany(IdeaIssue::class, 'idea_id', 'id');
    }

    public function improvements()
    {
        return $this->hasMany(IdeaIssue::class, 'idea_id', 'id')->where('type', 'IMPROVEMENT');
    }

    public function problems()
    {
        return $this->hasMany(IdeaIssue::class, 'idea_id', 'id')->where('type', 'PROBLEM');
    }

    public function evaluations()
    {
        return $this->hasMany(ProjectEvaluationRecord::class, 'idea_id', 'id');
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

        $this->total_gains = $this->evaluations()->where('project_evaluation_records.status', 'COMPLETED')->where('total_value', '>', 0)->sum('total_value');
        $this->total_losses = $this->evaluations()->where('project_evaluation_records.status', 'COMPLETED')->where('total_value', '<', 0)->sum('total_value');
        $this->consolidated_value = $this->total_gains + $this->total_losses;
        $this->total_evaluations = $this->evaluations()->where('project_evaluation_records.status', 'COMPLETED')->count();
    }
}
