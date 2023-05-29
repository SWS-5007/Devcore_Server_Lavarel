<?php

namespace App\Models;

use App\Lib\Models\HasPropertiesColumnTrait;
use App\Lib\Models\HasUUIDFieldTrait;
use App\Lib\Models\Resources\HasResourcesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdeaIssue extends Model
{
    use HasResourcesTrait, HasUserTrait, HasCompanyTrait, HasPropertiesColumnTrait;

    const TYPES = [
        'PROBLEM',
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
    public function resourcesOwnerId()
    {
        return $this->getKey();
    }

    public function getResourcesSection($type = null)
    {
        return 'idea-issue';
    }

    public function files()
    {
        return $this->resources();
    }


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

    public function feedback()
    {
        return $this->hasOne(IdeaIssueReply::class, 'idea_issue_id', 'id')->where(["status" => IdeaIssueReply::TYPES[0], "type" => "IDEAISSUE"]);
    }

    public function comments()
    {
        return $this->hasMany(IdeaIssueReply::class, 'issue_id', 'id')->where(["status" => IdeaIssueReply::TYPES[1], "type" => "IDEAISSUE"]);
    }

    public function parent()
    {
        return $this->morphTo('parent', 'parent_type', 'parent_id', 'id');
    }

//    public function value_shared()
//    {
//        return IdeaIssueReply::where("idea_issue_id", $this->id)->with("milestone_score_instances")->get();
//
//    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function idea()
    {
        return $this->belongsTo(Idea::class, 'idea_id', 'id');
    }


}
