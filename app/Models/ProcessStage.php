<?php

namespace App\Models;


use App\Lib\Models\HasDisplayOrderFieldTrait;
use App\Lib\Models\HasPropertiesColumnTrait;

class ProcessStage extends BaseModel
{
    use HasPropertiesColumnTrait, HasCompanyTrait, HasUserTrait, HasDisplayOrderFieldTrait, HasCompanyRolesTrait;

    protected static function boot()
    {
        parent::boot();
        static::deleting(function(ProcessStage $model) {
            // before delete() method call this
            $model->ideas()->delete();
            $model->toolIdeas()->delete();
            $model->issues()->delete();
            $operations = $model->operations()->get();
            foreach($operations as $operation){
                $operation->ideas()->delete();
                $operation->toolIdeas()->delete();
                $operation->issues()->delete();
                $phases = $operation->phases()->get();
                foreach($phases as $phase){
                    $phase->ideas()->delete();
                    $phase->toolIdeas()->delete();
                    $phase->issues()->delete();
                }
            }

            $projectStages = $model->projectStages()->all();
            foreach($projectStages as $projectStage){
                $projectStage->delete();
            }
        });
    }
    protected $userColumnName = 'author_id';


    public function author()
    {
        return $this->user();
    }

    protected function getDisplayOrderFilter()
    {
        return self::where('process_id', $this->process_id);
    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id', 'id');
    }

    public function operations()
    {
        return $this->hasMany(ProcessOperation::class, 'stage_id', 'id')->orderBy("d_order");
    }

    public function phases()
    {
        return $this->hasManyThrough(ProcessPhase::class, ProcessOperation::class, 'stage_id', 'operation_id', 'id', 'id');
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, ModelHasCompanyRole::class, 'model_id', 'company_role_id', 'id', 'company_role_id')->where('model_type', 'process_stage');
    }

    public function projectStages()
    {
        return $this->hasMany(ProjectStage::class, 'stage_id', 'id')->get();
    }

    public function ideas()
    {
        return $this->hasMany(Idea::class, 'parent_id', 'id')->where([
            'type' => 'PROCESS',
            'parent_type' => 'process_stage'
        ]);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'parent_id', 'id')->where([
            'parent_type' => 'process_stage'
        ]);
    }

    public function toolIdeas()
    {
        return $this->hasMany(Idea::class, 'parent_id', 'id')->where([
            'type' => 'TOOL',
            'parent_type' => 'process_stage'
        ]);
    }

}
