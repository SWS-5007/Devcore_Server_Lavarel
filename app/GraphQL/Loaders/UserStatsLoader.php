<?php

namespace App\GraphQL\Loaders;

use App\Models\IdeaIssue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;

class UserStatsLoader extends BatchLoader
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
        $result = $this->builder->with(['ideas', 'issues', 'improvements','problems'])
            ->findMany($parents->pluck('id'))
            ->mapWithKeys(function ($parent) {
                $stats = [];
                $stats['ideas'] = [];
                foreach($parent->ideas as $idea){
                    $stats['ideas'][] = [
                        'id' => $idea->id,
                    #    'description' => $idea->description
                    ];
                }

                $stats['issues'] = [];
                foreach($parent->issues as $issue){
                    $stats['issues'][] = [
                        'id' => $issue->id,
                       # 'description' => $issue->description,
                    ];
                }

//                $stats['improvements'] = [];
//                foreach($parent->improvements as $improvement){
//                    $stats['improvements'][] = [
//                        'id' => $improvement->id,
//                      #  'description' => $improvement->description,
//                    ];
//                }
//
//                $stats['problems'] = [];
//                foreach($parent->problems as $problem){
//                    $stats['problems'][] = [
//                        'id' => $problem->id,
//                     #   'description' => $problem->description,
//                    ];
//                }


                return [
                    $parent->getKey() => $stats,
                ];
            })->all();



        return $result;
    }
}
