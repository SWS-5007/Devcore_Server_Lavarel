<?php

namespace App\Models;

use App\Lib\Models\HasFileTrait;
use App\Lib\Models\HasPropertiesColumnTrait;
use App\Lib\Models\Resources\HasResourcesTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssueEffect extends BaseModel
{
    use HasUserTrait, HasPropertiesColumnTrait, HasCompanyTrait;


    protected $userColumnName = 'author_id';
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    const EFFECT_STATUS = [
        'CREATED',
        'ACTIVE',
        'PENDING',
        'CLOSED'
    ];

    public function issue()
    {
        return $this->belongsTo(Issue::class, 'issue_active_id', 'id');
    }

    public function templates()
    {
        return $this->hasMany(IssueEffectTemplate::class, 'effect_id', 'id');
    }

    public function author(): BelongsTo
    {
        return $this->user();
    }
}
