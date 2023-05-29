<?php

namespace App\GraphQL\Loaders;

use App\Models\Idea;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;

class ProcessStageRolesWithChildLoader extends BatchLoader
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var string
     */
    protected $relation;

    /**
     * @param Builder $builder
     * @param string  $relation
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Resolve the keys.
     *
     * The result has to be a map: [key => result]
     */
    public function resolve(): array
    {
        $parents = collect(Arr::pluck($this->keys, 'parent'));
        $result = $this->builder->with(['operations', 'phases', 'companyRoles', 'operations.companyRoles', 'phases.companyRoles'])->findMany($parents->pluck('id'))->mapWithKeys(function ($parent) {
            $roles = collect();
            $parent->companyRoles->map(function ($o) use($roles) {
                $roles->push($o);
            });
            $parent->operations->map(function ($p) use($roles) {
                $p->companyRoles->map(function ($o) use($roles) {
                    $roles->push($o);
                });
            });
            $parent->phases->map(function ($p) use($roles) {
                $p->companyRoles->map(function ($o) use($roles) {
                    $roles->push($o);
                });
            });

            return [
                $parent->getKey() => $roles->unique('id'),
            ];
        })->all();
        return $result;
    }
}
