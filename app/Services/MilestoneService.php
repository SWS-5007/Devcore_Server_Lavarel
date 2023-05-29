<?php

namespace App\Services;

use App\Lib\Models\Resources\Resource;
use App\Models\Milestone;
use App\Models\MilestoneUser;
use App\Models\User;
use App\Validators\MilestoneValidator;
use App\Validators\ProcessValidator;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function PHPSTORM_META\map;

class MilestoneService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(Milestone::class, false);
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

        $instance->title = $data->get('title', $instance->title);
        $instance->description = $data->get('description', $instance->description);
        $instance->required_score = $data->get('required_score', $instance->required_score);
        if ($option === 'create') {
            $instance->created_at = Carbon::now();
        } else {
            $instance->updated_at = Carbon::now();
        }
        return $instance;
    }

    protected function getValidator($data, $object, $option)
    {
        return new MilestoneValidator($data, $object, $option);
    }

    protected function created($data, $object)
    {
        $this->syncUsers($data, $object);



        return $object;
    }

    protected function updated($data, $object)
    {
        $this->syncUsers($data, $object);
        return $object;
    }

    protected function syncUsers($data, $object)
    {

       // create milestone user for current if none
        $currentCompanyUsers = User::where("company_id", $this->getUser()->company_id)->get();
        foreach($currentCompanyUsers as $companyUser) {
            if (!$companyUser->activeMilestoneUser()) {
                $companyUser->createDefaultMilestoneUser();
            }

            if($companyUser->user_engage_score >= $object->required_score){
                $lastMilestone = Milestone::orderBy("required_score", "desc")->first();
                $status = MilestoneUser::MILESTONE_USER_TYPES[1];
                $engageScore = $object->required_score;
                $rewarded = true;
                $activeUser = $companyUser->activeMilestoneUser();


                if ($lastMilestone->id === $object->id) {
                    //created in last milestone
                    $status = MilestoneUser::MILESTONE_USER_TYPES[0];
                    $engageScore = $companyUser->user_engage_score;
                    $activeUser->status = MilestoneUser::MILESTONE_USER_TYPES[1];
                    $rewarded = false;
                    if($activeUser->milestone){
                        $activeUser->engage_score = $activeUser->milestone->required_score;
                    }
                    $activeUser->save();
                }

                MilestoneUser::insert(
                    [
                        "user_id" => $companyUser->id,
                        "company_id" => $companyUser->company_id,
                        "engage_score" => $engageScore,
                        "milestone_id" => $object->id,
                        "rewarded" => $rewarded,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        "status" => $status
                    ]);
        }
    }
    }


    public function updateOrDeleteMany($data, $context){


        $items = collect($data);

        $milestones = Milestone::where("company_id", $this->getUser()->company_id)->with("users")->get();
        $users = MilestoneUser::whereNotNull("milestone_id")->where("company_id", $this->getUser()->company_id)->get();

        $updatedMilestones = [];

        foreach ($items as $key=>$item){
            $milestone = $milestones->where('id', $items[$key]["id"])->first();
            if($milestone){
                $current = $items[$key]["required_score"];
                $query = null;
                $milestone->title = $items[$key]["title"];
                $milestone->description = $items[$key]["description"];
                $milestone->required_score = $items[$key]["required_score"];
                $milestone->save();
                $updatedMilestones[] = $items[$key]["id"];

                //get users in range
                if(isset($items[$key + 1])) {
                    $next = $items[$key + 1]["required_score"] - 1;
                    $query = $users->whereBetween("engage_score", [$current, $next])->groupBy("user_id");

                } else {
                    $query = $users->where("engage_score", ">", $current)->groupBy("user_id");
                }

                if($query){

                    $milestone->users()->delete();
                    foreach($query as $userGroup){
                        $user = $userGroup->sortByDesc("engage_score")->first();
                        $milestoneUser = new MilestoneUser();
                        $milestoneUser->milestone_id = $items[$key]["id"];
                        $milestoneUser->company_id = $user->company_id;
                        $milestoneUser->status = $user->status;
                        $milestoneUser->engage_score = $user->engage_score;
                        $milestoneUser->user_id = $user->user_id;
                        $milestone->users()->save($milestoneUser);
                    }
                }
            }
        }



        $allDeleted = $milestones->whereNotIn('id', $updatedMilestones);

        foreach ($allDeleted->values() as $deleted) {
            $deletedUsers = $users->where(['milestone_id'=> $deleted->id]);

            foreach($deletedUsers->values() as $deletedUser){
                //Add points to users from deleted if active
                if($deletedUser->engage_score > $deletedUser->user->user_engage_score && $deletedUsers->status === "ACTIVE"){
                   $deletedUser->user->user_engage_score = $deletedUser->engage_score;
                   $deletedUser->user->save();
               }

            }
            $deleted->delete();
        }

        $currentCompanyUsers = User::where("company_id", $this->getUser()->company_id)->get();
        foreach($currentCompanyUsers as $companyUser) {
            //Set default active if all deleted
            if (!$companyUser->activeMilestoneUser()) {
                $biggestScoreMilestoneUser = MilestoneUser::where("user_id",$companyUser->id)
                    ->orderBy("engage_score", "desc")
                    ->first();

                $biggestScoreMilestoneUser->engage_score = $biggestScoreMilestoneUser->user->user_engage_score;
                $biggestScoreMilestoneUser->status = MilestoneUser::MILESTONE_USER_TYPES[0];
                $biggestScoreMilestoneUser->save();
            }

            //sync users active milestones
            $companyUser->notifyUserScoreUpdated();
        }
        return $milestones->whereIn('id', $updatedMilestones);
    }


    public function deleteMany($data)
    {
        $input = collect($data['ids']);
        $input->map(function ($id) {
            $this->delete($id);
        });
    }

    public function deleted($object)
    {
        return $object;
    }


    public function rewardMilestone($data)
    {
        $input = collect($data);
        $user = MilestoneUser::where(
            [
                "id" => $input->get('id'),
                "milestone_id" => $input->get('milestone_id')
            ])->first();

        if (!$user) {
            throw new NotFoundHttpException();
        }
            $user->rewarded = $input->get('rewarded');
            $user->save();
        return $user;
    }
}
