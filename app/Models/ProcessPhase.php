<?php

namespace App\Models;


use App\Lib\Models\HasDisplayOrderFieldTrait;
use App\Lib\Models\HasPropertiesColumnTrait;

class ProcessPhase extends BaseModel
{
    use HasPropertiesColumnTrait, HasCompanyTrait, HasUserTrait, HasDisplayOrderFieldTrait, HasCompanyRolesTrait;

    protected static function boot()
    {
        parent::boot();
        static::deleting(function(ProcessPhase $model) {
            // before delete() method call this
            $model->toolIdeas()->delete();
            $model->ideas()->delete();
            $model->issues()->delete();
        });
    }

    protected $userColumnName = 'author_id';


    public function author()
    {
        return $this->user();
    }

    protected function getDisplayOrderFilter()
    {
        return self::where('operation_id', $this->operation_id);
    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id', 'id');
    }

    public function operation()
    {
        return $this->belongsTo(ProcessOperation::class, 'operation_id', 'id');
    }

    public function ideas()
    {
        return $this->hasMany(Idea::class, 'parent_id', 'id')->where([
            'type'=>'PROCESS',
            'parent_type'=>'process_phase'
        ]);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'parent_id', 'id')->where([
            'parent_type' => 'process_phase'
        ]);
    }

    public function toolIdeas()
    {
        return $this->hasMany(Idea::class, 'parent_id', 'id')->where([
            'type'=>'TOOL',
            'parent_type'=>'process_phase'
        ]);
    }


    public function users()
    {
        return $this->hasManyThrough(User::class, ModelHasCompanyRole::class, 'model_id', 'company_role_id', 'id', 'company_role_id')->where('model_type', 'process_phase');
    }
}
