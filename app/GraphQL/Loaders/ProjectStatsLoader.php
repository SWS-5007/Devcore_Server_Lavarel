<?php

namespace App\GraphQL\Loaders;

use App\Models\Idea;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;

class ProjectStatsLoader extends BatchLoader
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
            ->with(['stages', 'issues', 'ideas'])
            ->findMany($parents->pluck('id'))
            ->mapWithKeys(function ($parent) {

                $stats = [];

                $stats['totalGains'] = $parent->total_gains;
                $stats['totalLosses'] = $parent->total_losses;
                $stats['consolidatedValue'] = $parent->consolidated_value;
                $stats['totalEvaluations'] = $parent->total_evaluations;

                $stats['stages'] = [];
                foreach ($parent->stages as $stage) {
                    $stats['stages'][] = [
                        'id' => $stage->id,
                        'totalGains' => $stage->total_gains,
                        'totalLosses' => $stage->total_losses,
                        'consolidatedValue' => $stage->consolidated_value,
                        'totalEvaluations' => $stage->total_evaluations,
                        'totalIssues' => $stage->issues_count,
                    ];
                }

                return [
                    $parent->getKey() => $stats,
                ];
            })->all();

        return $result;
    }
}
