<?php

namespace App\Models;

use App\Lib\Models\HasDisplayOrderFieldTrait;
use App\Lib\Models\HasPropertiesColumnTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Process extends Model
{
    use HasPropertiesColumnTrait, HasCompanyTrait, HasUserTrait, HasDisplayOrderFieldTrait, HasCompanyRolesTrait;

    protected $userColumnName = 'author_id';


    public function author()
    {
        return $this->user();
    }

    protected function getDisplayOrderFilter()
    {
        return self::where($this->primaryKey, '!=', $this->getKey())->where('company_id', $this->company_id);
    }

    public function stages()
    {
        return $this->hasMany(ProcessStage::class, 'process_id', 'id')->orderBy("d_order");
    }

    public function operations()
    {
        return $this->hasMany(ProcessOperation::class, 'process_id', 'id')->orderBy("d_order");
    }

    public function phases()
    {
        return $this->hasMany(ProcessPhase::class, 'process_id', 'id')->orderBy("d_order");
    }

    public function allIdeas(){
        return $this->hasMany(Idea::class, 'process_id', 'id');
    }

    public function ideas()
    {
        return $this->hasMany(Idea::class, 'process_id', 'id')->where('type', 'PROCESS');
    }

    public function toolIdeas()
    {
        return $this->hasMany(Idea::class, 'process_id', 'id')->where('type', 'TOOL');
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'process_id', 'id');
    }


    public function projects()
    {
        return $this->hasMany(Project::class, 'process_id', 'id');
    }

    public function idea_issues()
    {
        return $this->hasMany(IdeaIssue::class, 'process_id', 'id');
    }



    public function users($forceLoad = false)
    {

        // $relation = $this->hasMany(User::class);
        // $query = User::query()
        //     ->join('company_roles', 'users.company_role_id', 'company_roles.id')
        //     ->wherein('company_roles.id', function ($q) {
        //         $q->from('model_has_company_roles')
        //     });

        // $relation->setQuery(
        //     $query->getQuery()
        // );
        // return $relation;

        // if (!isset($this->users) || $forceLoad) {

        // }
        // $query = User::join('company_roles', 'company_role_id', 'id');
        // return new Relation($query, $this);

        // return $this->hasMany(User::class, 'process_id', 'id')->join('company_roles', function ($q) {
        //     return $q->join('model_has_company_roles', 'model_id', 'id');
        // });
        //return $query;
        // $relation=$this->newBelongsToMany(Process::query(), User::class, );
        // $relation->where('');
        // return $relation;
        //return $this->hasManyThrough(CompanyRole::class, ModelHasCompanyRole::class, 'model_id', 'id', 'id', 'company_role_id')->where('model_type', array_search(static::class, Relation::morphMap()) ?: static::class);
    }
}
