<?php

namespace App\Models;

use App\Lib\Models\HasDefaultImageTrait;
use App\Lib\Models\HasPropertiesColumnTrait;
use App\Lib\Utils;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyRole extends BaseModel
{
    use HasPropertiesColumnTrait, HasDefaultImageTrait, HasCompanyTrait;

    protected static function boot()
    {
        parent::boot();
        static::saving(function (CompanyRole $model) {
            if ($model->isDirty()) {
                //delete the default image when the parameters has changed
                if ($model->isDirty('name') || $model->isPropertyDirty('web:reportColor')) {
                    $model->deleteDefaultImage();
                }
            }
        });

        static::created(function($model){
            $model->createCompanyScoreInstance();
            $model->setCompetitiveRoles();
            $model->setPathPermissions();
        });
    }

    protected $imageColumn = 'avatar';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'properties' => 'array'
    ];

    protected $appends = ['avatar_url', 'report_color'];

    public function createCompanyScoreInstance(){
        $instance = new CompanyRoleScoreInstance();
        $instance->company_role_id = $this->id;
        $instance->consolidated_value = 0;
        $instance->company_id = $this->company_id;
        $instance->save();
    }

    public function getAvatarUrlAttribute()
    {
        return $this->getImageUrl();
    }


    //file config section
    public function getFileSection()
    {
        return 'company-roles';
    }

    public function getFileOwnerId()
    {
        return $this->id;
    }


    public function setCompetitiveRoles(){
        $company = $this->company()->first();
        $rolesInCompany = $company->companyRoles()->WithUserScores()->get()->sortBy("user_engage_scores");

        $roleScores = $rolesInCompany->map(function($role) use(&$total){
            $score = $role->user_engage_scores ? $role->user_engage_scores : 0;

        if(isset($role->roleScore)){
            $role->roleScore->setAttribute("consolidated_value", $score);
            return $role->roleScore;
        }
        });

        foreach($roleScores->values() as $key=>$score){

            $all = $roleScores->values();
            if($score && $score->company_role_id && $score->company_role_id == $this->id){

                $versusId = null;
                if(isset($all[$key+1]) && $all[$key+1]){
                    $versusId = $all[$key+1]->company_role_id;
                } else if(isset($all[$key-1]) && $all[$key-1]){
                    $versusId = $all[$key-1]->company_role_id;
                } else {
                    return;
                }
                $score->versus_period_start = Carbon::now()->endOfDay();
                $score->versus_period_end = Carbon::now()->endOfDay()->addDays(180);
                $score->versus_role_id  = $versusId;
                $score->save();
            }
        }
    }

    public function getReportColorAttribute()
    {
        return $this->getProperty("web:reportColor", Utils::getRandomColor($this->id));
    }

    public function setPathPermissions(){
        $company = $this->company()->first();
        $processes = $company->processes;
        $companyRoleIds = $company->companyRoles->map(function ($role) {
            return $role->id;
        })->toArray();
        $companyRoleIds[] = $this->id;

        if(count($processes) < 1) return;
        foreach($processes as $process) {
            $stages = $process->stages;
            $operations = $process->operations;
            $phases = $process->phases;

            foreach($stages as $stage) {
               // $this->info(sprintf('Syncing stage %s with all roles', $stage->title));
                $stage->syncCompanyRoles($companyRoleIds);
                $stage->save();
            }
            foreach($operations as $operation) {
             //   $this->info(sprintf('Syncing operation %s with all roles', $operation->title));
                $operation->syncCompanyRoles($companyRoleIds);
                $operation->save();
            }
            foreach($phases as $phase) {
              //  $this->info(sprintf('Syncing phase %s with all roles', $phase->title));
                $phase->syncCompanyRoles($companyRoleIds);
                $phase->save();
            }
        }
    }


    protected function shouldRegenerateDefaultImage()
    {
        return $this->isDirty(["name"]);
    }

    public function roleScore()
    {
        return $this->hasOne(CompanyRoleScoreInstance::class, 'company_role_id','id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'company_role_id', 'id');
    }

    public function scopeWithUserScores($query)
    {
        return $query->withCount(['users',
            'users as user_engage_scores' =>  function ($query){
            $query->select(DB::raw('sum(user_engage_score)'));
            }
        ]);
    }

    public function issues(){
        return $this->hasManyThrough(Issue::class, User::class, 'company_role_id', 'author_id', 'id', 'id');
    }

    public function getDefaultImageText()
    {
        $reportColor = $this->getReportColorAttribute();
        return urlencode(preg_replace("/[^a-zA-Z0-9 ]+/", "", $this->name)) . "&color=" . $reportColor['contrast'] . '&background=' . $reportColor['color'];
    }
}
