<?php

namespace App\GraphQL\Loaders;

use App\Models\Idea;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;

class ProcessStageStatsLoader extends BatchLoader
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
        $result = $this->builder
            ->withCount([
                'operations', 'phases', 'ideas', 'issues', 'toolIdeas',
                'ideas as new_ideas_count' => function (Builder $builder) {
                    $builder->where('status', 'NEW');
                }, 'ideas as testing_ideas_count' => function (Builder $builder) {
                    $builder->where('status', 'TESTING');
                }, 'ideas as adopted_ideas_count' => function (Builder $builder) {
                    $builder->where('status', 'ADOPTED');
                },
                'toolIdeas as new_tool_ideas_count' => function (Builder $builder) {
                    $builder->where('status', 'NEW');
                }, 'toolIdeas as testing_tool_ideas_count' => function (Builder $builder) {
                    $builder->where('status', 'TESTING');
                }, 'toolIdeas as adopted_tool_ideas_count' => function (Builder $builder) {
                    $builder->where('status', 'ADOPTED');
                },
            ])
            ->findMany($parents->pluck('id'))->mapWithKeys(function ($parent) {

                $stats = [];

                $stats['operations'] = [
                    'total' => $parent->operations_count,
                ];

                $stats['phases'] = [
                    'total' => $parent->phases_count,
                ];

                $stats['ideas'] = [
                    'total' => $parent->ideas_count,
                ];

                $stats['ideas']['status'] = [
                    'NEW' => $parent->new_ideas_count,
                    'ADOPTED' => $parent->adopted_ideas_count,
                    'TESTING' => $parent->testing_ideas_count,
                ];

                $stats['tool_ideas'] = [
                    'total' => $parent->tool_ideas_count,
                ];


                $stats['tool_ideas']['status'] = [
                    'NEW' => $parent->new_tool_ideas_count,
                    'ADOPTED' => $parent->adopted_tool_ideas_count,
                    'TESTING' => $parent->testing_tool_ideas_count,
                ];

                $stats['issues'] = [
                    'total' => $parent->issues_count,
                ];

                return [
                    $parent->getKey() => $stats,
                ];
            })->all();
        return $result;
    }
}
