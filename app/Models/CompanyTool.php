<?php

namespace App\Models;

use App\Lib\Models\HasPropertiesColumnTrait;
use App\Lib\Utils;
use Illuminate\Database\Eloquent\Model;

use \App\Lib\Models\HasDefaultImageTrait;

class CompanyTool extends BaseModel
{
    use HasPropertiesColumnTrait, HasDefaultImageTrait, HasCompanyTrait;

    ///////////
    protected $imageColumn = 'avatar';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'properties' => 'array'
    ];
    protected static function boot()
    {
        parent::boot();
        static::saving(function (CompanyTool $model) {
            if ($model->isDirty()) {
                //delete the default image when the parameters has changed
                if ($model->isDirty('name') || $model->isPropertyDirty('web:reportColor')) {
                    $model->deleteDefaultImage();
                }
            }
        });
    }

    public function getAvatarUrlAttribute()
    {
        return $this->getImageUrl();
    }

    //file config section
    public function getFileSection()
    {
        return 'company-tools';
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
    ////////////////
    const STATUS = [
        'ACTIVE',
        'INACTIVE',
    ];

    const PRICE_MODELS = [
        'PROJECT',
        'LICENSE',
        'ONE_TIME_PAYMENT'
    ];

    public $with = ['tool', 'modules', 'prices'];
    public $appends = ['name', 'type', 'avatar_url', 'report_color'];

    public function tool()
    {
        return $this->belongsTo(Tool::class, 'tool_id', 'id');
    }

    public function getNameAttribute()
    {
        return $this->tool->name;
    }

    public function getTypeAttribute()
    {
        return $this->tool->type;
    }

    public function modules()
    {
        return $this->hasMany(CompanyTool::class, 'parent_id', 'id');
    }

    public function prices()
    {
        return $this->hasMany(CompanyToolPrice::class, 'company_tool_id', 'id');
    }

    public function ideas()
    {
        return $this->hasMany(Idea::class, 'parent_id', 'id')->where('type', 'TOOL');
    }

    public function evaluationRecords()
    {
        return $this->hasManyThrough(ProjectEvaluationRecord::class, Idea::class, 'company_tool_id', 'idea_id', 'id', 'id');
    }

    public function calculateTotals()
    {
        $this->total_gains = $this->evaluationRecords()->where('project_evaluation_records.status', 'COMPLETED')->where('total_value', '>', 0)->sum('total_value');
        $this->total_losses = $this->evaluationRecords()->where('project_evaluation_records.status', 'COMPLETED')->where('total_value', '<', 0)->sum('total_value');
        $this->consolidated_value = $this->total_gains + $this->total_losses;
        $this->total_evaluations = $this->evaluationRecords()->where('project_evaluation_records.status', 'COMPLETED')->count();
    }
}
