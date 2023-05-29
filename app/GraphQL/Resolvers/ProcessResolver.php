<?php

namespace App\GraphQL\Resolvers;

use App\GraphQL\Loaders\ProcessStatsLoader;
use App\Lib\GraphQL\GenericResolver;
use App\Models\Process;
use App\Services\ProcessService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ProcessResolver extends GenericResolver
{
    public function __construct()
    {
        parent::__construct('process/process', Process::class, 'id', true);
    }

    protected function createServiceInstance()
    {
        return ProcessService::instance();
    }

    /**
     * Resolve Stats
     *
     * @param Process        $process
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $info
     *
     * @return \GraphQL\Deferred
     */
    public function stats(Process $process, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        $dataloader = BatchLoader::instance(
            ProcessStatsLoader::class,
            $info->path,
            [
                'builder'  => \App\Models\Process::query(),
            ]
        );
        
        return $dataloader->load(
            $process->getKey(),
            ['parent' => $process]
        );
    }
}
