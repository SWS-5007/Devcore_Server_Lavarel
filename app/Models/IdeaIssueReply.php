<?php

namespace App\Models;

use App\Lib\Models\HasFileTrait;
use App\Lib\Models\HasPropertiesColumnTrait;
use App\Lib\Models\Resources\HasResourcesTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;


class IdeaIssueReply extends BaseModel
{
    use HasUserTrait, HasCompanyTrait;

    const TYPES = [
        'FEEDBACK',
        'COMMENT',
    ];

    const REFERENCES = [
        'IDEA',
        'ISSUE',
        'IDEAISSUE'
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

    public function parent()
    {
        return $this->morphTo('parent', 'type', 'parent_id', 'id');
    }

    public function issue()
    {
        return $this->belongsTo(Issue::class, 'issue_id', 'id');
    }

    public function idea()
    {
        return $this->belongsTo(Idea::class, 'idea_id', 'id');
    }

    public function idea_issue()
    {
        return $this->belongsTo(IdeaIssue::class, 'idea_issue_id', 'id');
    }

    public function score_instance()
    {
        return $this->hasOne(MilestoneScoreInstance::class, 'reply_id', 'id');
    }

}
