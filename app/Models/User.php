<?php

namespace App\Models;

use App\Lib\GraphQL\Exceptions\NotFoundException;
use App\Lib\Models\HasDefaultImageTrait;
use App\Lib\Models\HasPropertiesColumnTrait;
use App\Lib\Models\Notification;
use App\Lib\Utils;
use App\Notifications\ResetPasswordNotification;
use App\Services\UserDeviceService;
use App\UserDevice;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{
    use Notifiable, HasDefaultImageTrait, HasPropertiesColumnTrait, HasCompanyTrait, HasRoles;

    const USER_STATUSES = [
        'PENDING',
        'ACTIVE',
        'INACTIVE',
        'TRASHED'
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function($model){
            if(!$model->is_super_admin){
                MilestoneUser::create([
                    'user_id' => $model->id,
                    'company_id' => $model->company_id,
                    'created_at' => Carbon::now(),
                    'engage_score' => 0,
                ]);

            }
        });

        static::saving(function (User $model) {
            if ($model->isDirty()) {
                //delete the default image when the parameters has changed
                if ($model->isDirty('first_name') || $model->isDirty('last_name') || $model->isPropertyDirty('web:reportColor')) {
                    $model->deleteDefaultImage();
                }
            }
        });
    }

    protected $imageColumn = 'avatar';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'phone', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['avatar_url', 'full_name', 'report_color', 'all_permissions'];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function tokens()
    {
        return $this->morphMany(Token::class, 'owner', 'field_name', 'field_value');
    }

    public function experienceUsers()
    {
        return $this->hasMany(ExperienceUser::class, 'user_id', 'id');
    }


    public function getUserRank(){
        //reverse so index 14 means rank 14
        $users = MilestoneUser::where("status", "ACTIVE")->orderBy("engage_score", "DESC")->get()->toArray();
        $index = 0;
        foreach(array_values($users) as $i=>$value){
            if($value["user_id"] == $this->id){
                $index = $i;
            }
        }

        return $index + 1;
    }

    public function activeMilestone(){
        if($this->activeMilestoneUser()) {
            return $this->activeMilestoneUser()->milestone;
        }
    }

    public function userIdeas(){
        return $this->hasMany(Idea::class, 'author_id', 'id');
    }

    public function userIdeaIssues(){
        return $this->hasMany(IdeaIssue::class, 'author_id', 'id');
    }

    public function getsUserValueFromIdea(Idea $idea){
        $sharedValue = 0;
        if($idea->feedback()->count()>0){
            $feedbackId = $idea->feedback()->first()->id;
            $scoreInstance = MilestoneScoreInstance::where("reply_id", $feedbackId)->first();
            $sharedValue += $scoreInstance ? $scoreInstance->score : 0;
        }

        foreach($idea->idea_issues()->get() as $ideaIssue){
            if($ideaIssue->feedback()->count()>0){
                $feedbackId = $ideaIssue->feedback()->first()->id;
                $scoreInstance = MilestoneScoreInstance::where("reply_id", $feedbackId)->first();
                $sharedValue += $scoreInstance ? $scoreInstance->score : 0;
            }
        }
        return $sharedValue;
    }


    public function getUserSharedValue(){
        $sharedValue = 0;
        $calculated = [];

        $ideasByAuthor = $this->userIdeas()->get();
        //all ideas done by user and their cumulative score
        foreach($ideasByAuthor as $idea){
            if(!in_array($idea->id, $calculated)){
                $sharedValue += $this->getsUserValueFromIdea($idea);
            }
            $calculated[] = $idea->id;
        }

        $ideaIssuesByAuthor = $this->userIdeaIssues()->get();

        foreach($ideaIssuesByAuthor as $ideaIssue){
            $idea = $ideaIssue->idea()->first();
            if($idea){
                if(!in_array($idea->id, $calculated)){
                    $sharedValue += $this->getsUserValueFromIdea($idea);
                }
                $calculated[] = $idea->id;
            }

        }

        return $sharedValue;
    }

    public function getUsersProjectIdeaUsage(){

        $projects = $this->projects()->get();
        $uniqueProjectUsers = [];

            foreach($projects as $project){
                $users = $project->projectUsers()->get();
                foreach($users as $user){
                    if(!in_array($user->user_id, $uniqueProjectUsers)){
                        $uniqueProjectUsers[] = $user->user_id;
                  }
                }
             }

        return count($uniqueProjectUsers);
    }

    public function getRoleScores(){

        $rolesByUserScore = CompanyRole::WithUserScores()->with("roleScore")->get();
        $current = $rolesByUserScore->where("id", $this->company_role_id)->first();
        $roleScores = [];
        if($current && $current->roleScore){
            $versusId = $current->roleScore->versus_role_id;
            $competition = $rolesByUserScore->where("id", $versusId)->first();



            $roleScores = [
                [
                    "isAgainst"=> $this->company_role_id !== $current->id,
                    "roleId"=>$current->id,
                    "role"=>$current->name,
                    "roleValue"=>$current->user_engage_scores ? $current->user_engage_scores : 0,
                    "userCount" => $current->users_count,
                ],
            ];
            if($competition){
                $score = $competition->user_engage_scores ? $competition->user_engage_scores : 0;
                $roleScores[] = [
                    "isAgainst"=> $this->company_role_id !== $competition->id,
                    "roleId"=>$competition->id,
                    "role"=>$competition->name,
                    "roleValue"=>$score,
                    "userCount" => $competition->users_count
                ];
            }
        }
        return $roleScores;
    }


    public function milestoneUsers(){
        return $this->hasMany(MilestoneUser::class, 'user_id', 'id');
    }

    public function activeMilestoneUser(){
        $active = $this->milestoneUsers()->where('status', 'ACTIVE')->first();
        return $active;
    }


    public function createDefaultMilestoneUser(){

        $milestoneUser = new MilestoneUser();
        $milestoneUser->fill([
            "user_id"=>$this->id,
            "engage_score"=>$this->user_engage_score,
            "rewarded"=> false,
            "status"=> MilestoneUser::MILESTONE_USER_TYPES[0]
        ]);
        $milestoneUser->save();
    }

    public function increaseEngagementScore($authorId,$replyId, $points = 0){
        if($this->activeMilestoneUser()){
            $this->activeMilestoneUser()->increaseScore($authorId, $replyId, $points);
        }
    }

    //Without payload set user as payload
    public function notifyUserScoreUpdated($data = null){
        $payload = $data;
        if(!$data){
            $payload = $this;
        }

        $payload->makeHidden(["all_permissions", "company_role"])->toArray();
        $message = new Notification('userUpdated', $payload);

        \Nuwave\Lighthouse\Execution\Utils\Subscription::broadcast('userUpdated', $message);
    }

    //Without payload set user as payload
//    public function notifyUserReplied($data = null){
//        $payload = $data;
//        if(!$data){
//            $payload = $this;
//        }
//        $message = new Notification('UserReplied', $payload);
//
//        \Nuwave\Lighthouse\Execution\Utils\Subscription::broadcast('userReplied', $message);
//    }

    public function getAvatarUrlAttribute()
    {
        return $this->getImageUrl();
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    //file config section
    public function getFileSection()
    {
        return 'users';
    }

    public function getFileOwnerId()
    {
        return $this->id;
    }

    public function getReportColorAttribute()
    {
        $color = $this->companyRole ? $this->companyRole->report_color : Utils::getRandomColor($this->id);
        return $this->getProperty("web:reportColor", $color);
    }


    public function getDefaultImageText()
    {
        $reportColor = $this->getReportColorAttribute();
        return urlencode(preg_replace("/[^a-zA-Z0-9 ]+/", "", $this->full_name)) . "&color=" . $reportColor['contrast'] . '&background=' . $reportColor['color'];
    }

    protected function shouldRegenerateDefaultImage()
    {
        return $this->isDirty(["first_name", "last_name", "company_role_id"]);
    }


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify((new ResetPasswordNotification($token))->locale($this->lang));
    }

    public function routeNotificationForFcm()
    {
        foreach($this->devices as $device){
            if($device->type=="android"){
                return $device->token;
            }
        }

        return '';
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return config('mail.fixed_address') ? config('mail.fixed_address') : $this->email;
    }

    public function companyRole()
    {
        return $this->belongsTo(CompanyRole::class, 'company_role_id', 'id');
    }

    public function getAllPermissionsAttribute()
    {
        return $this->getAllPermissions();
    }

    public function preferredLocale()
    {
        return $this->lang;
    }

    public function principalRole()
    {
        return $this->roles ? $this->roles->first() : null;
    }

    public function principalRoleId()
    {
        return $this->principalRole() ? $this->principalRole()->id : null;
    }


    public function principalRoleName()
    {
        return $this->principalRole() ? $this->principalRole()->name : null;
    }

    public function evaluationRecords()
    {
        return $this->hasMany(ProjectEvaluationRecord::class, 'author_id', 'id');
    }

    public function devices()
    {
        return $this->hasMany(UserDevice::class, 'user_id', 'id')->orderBy('created_at','desc');
    }

    public function calculateMoneyValue($dimension, $value, $unit, Carbon $from, Carbon $to)
    {
        $montlyCosts = $this->yearly_costs / 12; //montly costs
        $weeklyCosts = $this->yearly_costs / 52.1429; //weekly costs
        $dailyCosts = $weeklyCosts / count(config('custom.weekdays', 5)); //dayly costs (40/hrs week)
        $hourlyCosts = $weeklyCosts / 40; //8hrs per day
        $factor = 1;
        if ($unit === 'WEEK') {
            $start = new \DateTime($from->toDateString());
            $end = new \DateTime($to->toDateString());
            // otherwise the  end date is excluded (bug?)
            $end->modify('+1 day');

            $interval = $end->diff($start);

            // total days
            $days = $interval->days;

            // create an iterateable period of date (P1D equates to 1 day)
            $period = new \DatePeriod($start, new \DateInterval('P1D'), $end);

            // best stored as array, so you can add more than one
            foreach ($period as $dt) {
                $curr = $dt->format('w');

                // substract if Saturday or Sunday
                if (!in_array($curr, config('custom.weekdays'))) {
                    $days--;
                } else if (in_array($dt->format('Y-m-d'), config('custom.holidays'))) {
                    $days--;
                }
            }
            $factor = ($days / count(config('custom.weekdays')));

        } else if ($unit === 'MONTH') {
            $factor = $to->floatDiffInMonths($from);
        }
        if ($dimension === 'TIME') {
            return $value * $factor  * $hourlyCosts;
        } else {
            return $value * $factor;
        }
    }

    public function projects()
    {
        return $this->hasManyThrough(Project::class, ProjectUser::class, 'user_id', 'id', 'id', 'project_id');
    }

    public function calculateTotals()
    {
        $this->total_gains = $this->evaluationRecords()->where('project_evaluation_records.status', 'COMPLETED')->where('total_value', '>', 0)->sum('total_value');
        $this->total_losses = $this->evaluationRecords()->where('project_evaluation_records.status', 'COMPLETED')->where('total_value', '<', 0)->sum('total_value');
        $this->consolidated_value = $this->total_gains + $this->total_losses;
        $this->total_evaluations = $this->evaluationRecords()->where('project_evaluation_records.status', 'COMPLETED')->count();
        return "";
    }
}
