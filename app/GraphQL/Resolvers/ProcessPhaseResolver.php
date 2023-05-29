<?php

namespace App\GraphQL\Resolvers;

use App\GraphQL\Loaders\ProcessPhaseStatsLoader;
use App\Lib\GraphQL\GenericResolver;
use App\Models\ProcessPhase;
use App\Services\ProcessPhaseService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ProcessPhaseResolver extends GenericResolver
{
    use HasUpdateOrderMethodTrait;

    public function __construct()
    {
        parent::__construct('process/process', ProcessPhase::class, 'id', true);
    }

    protected function createServiceInstance()
    {
        return ProcessPhaseService::instance();
    }

    /**
     * Resolve Stats
     *
     * @param ProcessPhase        $process
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $info
     *
     * @return \GraphQL\Deferred
     */
    public function stats(ProcessPhase $process, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        $dataloader = BatchLoader::instance(
            ProcessPhaseStatsLoader::class,
            $info->path,
            [
                'builder'  => \App\Models\ProcessPhase::query(),
            ]
        );
        
        return $dataloader->load(
            $process->getKey(),
            ['parent' => $process]
        );
    }
}
