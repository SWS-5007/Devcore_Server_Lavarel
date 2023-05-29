<?php

namespace App\Models;

use App\Lib\Models\HasDisplayOrderFieldTrait;
use App\Lib\Models\HasPropertiesColumnTrait;

class ProcessOperation extends BaseModel
{
    use HasPropertiesColumnTrait, HasCompanyTrait, HasUserTrait, HasDisplayOrderFieldTrait, HasCompanyRolesTrait;

    protected $userColumnName = 'author_id';

    protected static function boot()
    {
        parent::boot();
        static::deleting(function(ProcessOperation $model) {
            // before delete() method call this
            $model->toolIdeas()->delete();
            $model->ideas()->delete();
            $model->issues()->delete();
            $phases = $model->phases()->get();
            foreach($phases as $phase){
                $phase->toolIdeas()->delete();
                $phase->ideas()->delete();
                $phase->issues()->delete();
            }
        });
    }

    public function author()
    {
        return $this->user();
    }

    protected function getDisplayOrderFilter()
    {
        return self::where('stage_id', $this->stage_id);
    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id', 'id');
    }

    public function stage()
    {
        return $this->belongsTo(ProcessStage::class, 'stage_id', 'id');
    }

    public function phases()
    {
        return $this->hasMany(ProcessPhase::class, 'operation_id', 'id')->orderBy("d_order");
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, ModelHasCompanyRole::class, 'model_id', 'company_role_id', 'id', 'company_role_id')->where('model_type', 'process_operation');
    }

    public function ideas()
    {
        return $this->hasMany(Idea::class, 'parent_id', 'id')->where([
            'type' => 'PROCESS',
            'parent_type' => 'process_operation'
        ]);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'parent_id', 'id')->where([
            'parent_type' => 'process_operation'
        ]);
    }

    public function toolIdeas()
    {
        return $this->hasMany(Idea::class, 'parent_id', 'id')->where([
            'type' => 'TOOL',
            'parent_type' => 'process_operation'
        ]);
    }
}
