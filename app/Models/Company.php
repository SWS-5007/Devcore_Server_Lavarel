<?php

namespace App\Models;

use App\Lib\Models\HasDefaultImageTrait;
use App\Lib\Models\HasPropertiesColumnTrait;
use App\Lib\Utils;
use Illuminate\Support\Facades\Log;

class Company extends BaseModel
{
    use HasPropertiesColumnTrait, HasDefaultImageTrait;

    const COMPANY_STATUSES = [
        'ACTIVE',
        'INACTIVE',
        'TRASHED'
    ];

    // protected $fillable = ['name','currencyCode','deleteLogo'];

    protected static function boot()
    {
        parent::boot();
        static::saving(function (Company $model) {
            if ($model->isDirty()) {
                //delete the default image when the parameters has changed
                if ($model->isDirty('name') || $model->isPropertyDirty('web:reportColor')) {
                    $model->deleteDefaultImage();
                }
            }
        });
        static::created(function (Company $model) {
            $model->setDefaultMilestones();
            $model->setDefaultExperienceTasks();
        });

        static::deleting(function(Company $model) { // before delete() method call this
            $model->ideas()->delete();
            $model->companyTools()->delete();
            $model->processes()->delete();
            $model->users()->delete();
            $model->companyRoles()->delete();

            // do the rest of the cleanup...
       });

    }

    protected $imageColumn = 'logo';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'properties' => 'array'
    ];

    protected $appends = ['logo_url', 'report_color'];

    public function setDefaultMilestones(){
        Milestone::insert([
            [
                "company_id" => $this->id,
                "title"=>"Silver Medal",
                "required_score"=> 100,
                "description" => "Share ideas, issue or improve existing instructions to earn more score!"
            ],
            [
                "company_id" => $this->id,
                "title"=>"Gold Medal",
                "required_score"=> 250,
                "description" => "Share ideas, issue or improve existing instructions to earn more score!"
            ],
            [
                "company_id" => $this->id,
                "title"=>"Diamond Metal",
                "required_score"=> 1000,
                "description" => "Share ideas, issue or improve existing instructions to earn more score!"
            ],
            [
                "company_id" => $this->id,
                "title"=>"Super Bronze Medal",
                "required_score"=> 3000,
                "description" => "Share ideas, issue or improve existing instructions to earn more score!"
            ],
            [
                "company_id" => $this->id,
                "title"=>"Super Silver Medal",
                "required_score"=> 9000,
                "description" => "Share ideas, issue or improve existing instructions to earn more score!"
            ],
            [
                "company_id" => $this->id,
                "title"=>"Super Gold Medal",
                "required_score"=> 15000,
                "description" => "Share ideas, issue or improve existing instructions to earn more score!"
            ]
       ] );
    }

    public function setDefaultExperienceTasks(){
        $quests = [
            [
                "name" => "Evaluate",
                "required_points" => 20,
            ],
            [
                "name" => "Share Idea",
                "required_points" => 3
            ],
            [
                "name"=> "Report Problem",
                "required_points" => 3
            ],
            [
                "name"=>"Improve Idea",
                "required_points" => 3
            ],
            [
                "name" => "Study Instructions",
                "required_points" => 20
            ],
            [
                "name"=>"Complete Project",
                "required_points" => 1
            ]
        ];

        $relations = [];
        foreach ($quests as $quest){
            $questRelation = [
                "company_id" => $this->id,
                "title"=>$quest["name"],
                "required_points"=> $quest["required_points"],
            ];
            $relations[] = $questRelation;
        }

        ExperienceQuest::insert($relations);
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'code', 'currency_code');
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industry_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'company_id', 'id');
    }

    public function companyRoles()
    {
        return $this->hasMany(CompanyRole::class, 'company_id', 'id');
    }

    public function experienceQuests()
    {
        return $this->hasMany(ExperienceQuest::class, 'company_id', 'id');
    }

    public function getLogoUrlAttribute()
    {
        return $this->getImageUrl();
    }

    //file config section
    public function getFileSection()
    {
        return 'companies';
    }

    public function getFileOwnerId()
    {
        return $this->id;
    }

    public function getReportColorAttribute()
    {
        return $this->getProperty("web:reportColor", Utils::getRandomColor($this->id));
    }

    protected function shouldRegenerateDefaultImage()
    {
        return $this->isDirty(["name"]);
    }

    public function getDefaultImageText()
    {
        $reportColor = $this->getReportColorAttribute();
        return urlencode(preg_replace("/[^a-zA-Z0-9 ]+/", "", $this->name)) . "&color=" . $reportColor['contrast'] . '&background=' . $reportColor['color'];
    }

    public function companyTools()
    {
        return $this->hasMany(CompanyTool::class, 'company_id', 'id');
    }

    public function processes()
    {
        return $this->hasMany(Process::class, 'company_id', 'id');
    }

    public function ideas(){
        return $this->hasMany(Idea::class, 'company_id', 'id');
    }
}
