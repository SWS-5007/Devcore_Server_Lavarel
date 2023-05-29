<?php

namespace App\GraphQL\Loaders;

use App\Models\Idea;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;

class MilestoneStatsLoader extends BatchLoader
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
                'problems', 'improvements', 'evaluations',
                'evaluations as missing_evaluations_count' => function (Builder $builder) {
                    $builder->where('status', 'MISSING');
                },
                'evaluations as completed_evaluations_count' => function (Builder $builder) {
                    $builder->where('status', 'COMPLETED');
                },
                'evaluations as pending_evaluations_count' => function (Builder $builder) {
                    $builder->where('status', 'PENDING');
                },
                'evaluations as skipped_evaluations_count' => function (Builder $builder) {
                    $builder->where('status', 'SKIPPED');
                },
            ])
            ->findMany($parents->pluck('id'))->mapWithKeys(function ($parent) {

                $stats = [];

                $stats['improvements'] = [
                    'total' => $parent->improvements_count,
                ];
                $stats['problems'] = [
                    'total' => $parent->problems_count,
                ];
                $stats['evaluations'] = [
                    'total' => $parent->evaluations_count,
                    'status'=>[
                        'MISSING'=>$parent->missing_evaluations_count,
                        'COMPLETED'=>$parent->completed_evaluations_count,
                        'PENDING'=>$parent->pending_evaluations_count,
                        'SKIPPED'=>$parent->skipped_evaluations_count,
                    ]
                ];

                return [
                    $parent->getKey() => $stats,
                ];
            })->all();
        return $result;
    }
}
