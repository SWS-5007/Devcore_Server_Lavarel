<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class MilestoneScoreInstance extends Model
{
    protected $guarded = ['id'];
    protected static function boot()
    {
        parent::boot();
        static::created(function (MilestoneScoreInstance $model) {
            $milestoneUser = $model->milestoneUser()->first();
            $user = $milestoneUser->user()->first();
            $user->user_engage_score += $model->score;
            $user->save();
            $user->notifyUserScoreUpdated();
        });
    }
    const INSTANCE_TYPE = [
        'IDEA',
        'ISSUE',
        'COMMENT'
    ];

    public function user()
    {
        return $this->hasOneThrough(User::class, MilestoneUser::class, "user_id","id","id","id");
    }

    public function milestoneUser()
    {
        return $this->belongsTo(MilestoneUser::class, 'milestone_user', 'id');
    }


    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
}

