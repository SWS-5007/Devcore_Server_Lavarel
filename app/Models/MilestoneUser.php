<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class MilestoneUser extends Model
{
    use HasCompanyTrait;

    protected $fillable = ['user_id', 'company_id', 'created_at','engage_score'];
    const MILESTONE_USER_TYPES = [
        'ACTIVE',
        'ARCHIVE'
    ];

    public static $points = 0;
    public static $authorId = null;
    public static $replyId = null;

    protected static function boot()
    {
        parent::boot();

        static::updating(function(MilestoneUser $model){
            //if updating event occurred from points increase
            //if local points above zero we know point increase came from reply
            //else new milestone created. Handled in milestone service
            if($model->getLocalPoints() > 0) {
                $milestones = $model->allMilestones();
                $currentLevel = $milestones
                    ->where('required_score', '<=', $model->engage_score)->last();
                if($currentLevel){
                    $isNextLevel = $currentLevel->id != $model->milestone_id || !$model->milestone_id ;

                    if($isNextLevel){
                        $clone = $model->replicate();
                        $clone->rewarded = false;
                        $clone->engage_score = $model->engage_score;
                        $clone->milestone_id = $currentLevel->id ?? $milestones->first()->id;
                        $clone->status = "ACTIVE";
                        $clone->save();
                        $model->status = "ARCHIVE";
                        $model->engage_score -= $model->getLocalPoints();
                    }
                }
            }

        });
    }

    public function allMilestones()
    {
        return Milestone::orderBy("required_score")->get();
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class, 'milestone_id', 'id');
    }

    public function getLocalPoints()
    {
        return self::$points;
    }

    public function createScoreInstance()
    {
        if(self::$authorId && self::$points && self::$replyId){

            $instance = new MilestoneScoreInstance();
            $instance->milestone_user = $this->id;
            $instance->author_id = self::$authorId;
            $instance->score = self::$points;
            $instance->reply_id = self::$replyId;
            $instance->save();
        }
    }

    public function increaseScore($authorId, $replyId, $points)
    {
        self::$points = $points;
        self::$authorId = $authorId;
        self::$replyId = $replyId;

        $this->fill(["engage_score" => $this->engage_score + $points]);

        $this->save();
        $this->createScoreInstance();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
