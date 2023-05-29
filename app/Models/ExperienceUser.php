<?php

namespace App\Models;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExperienceUser extends BaseModel
{
    use HasUserTrait, HasCompanyTrait;

   protected $fillable = ['quest_id', 'experience_points'];

    public function experienceQuest()
    {
        return $this->belongsTo(ExperienceQuest::class, 'quest_id', 'id');
    }

    public function levelRequiredScore(){
        $required = $this->experienceQuest()->required_points;
        return $required;
    }
}
