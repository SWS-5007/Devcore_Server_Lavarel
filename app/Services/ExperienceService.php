<?php

namespace App\Services;

use App\Lib\Services\GenericService;
use App\Models\ExperienceQuest;
use App\Models\ExperienceUser;
use App\Models\Industry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ExperienceService extends GenericService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(ExperienceUser::class, false);
    }

    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }


    protected function fillFromArray($option, $data, $instance)
    {
        $data = collect($data);
        $receiver = User::where("id", $data->get("user_id"))->first();

        $instance->company_id = $receiver->company_id;
        $instance->quest_id = $data->get("quest_id", $instance->quest_id);
        $instance->user_id = $data->get("user_id", $instance->user_id);
        return $instance;
    }

    public function increaseExperience($input)
    {
        $data = collect($input);
        $user = null;
        $receiver = User::where("id", $data->get("user_id"))->first();
        $experienceUser = $receiver->experienceUsers()->where("quest_id", $data->get("quest_id"))->first();

        if($experienceUser){
            if($data->get("experience_points")){
                $experienceUser->experience_points = $experienceUser->experience_points + $data->get("experience_points");
            } else {
                $experienceUser->experience_points = $experienceUser->experience_points + 1;
            }

            $experienceUser->updated_at = Carbon::now();
            $levelPointsMultiplied = $experienceUser->experienceQuest->required_points * $experienceUser->level;
            if($levelPointsMultiplied <= $experienceUser->experience_points){
                $experienceUser->experience_points = 0;
                $experienceUser->level += 1;
            }
            $user = $this->update($experienceUser, $data);

        } else {
            $user = $this->create($data);
        }

        return $user;

    }
}
