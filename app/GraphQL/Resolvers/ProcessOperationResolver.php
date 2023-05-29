<?php

namespace App\GraphQL\Resolvers;

use App\GraphQL\Loaders\ProcessOperationStatsLoader;
use App\Lib\GraphQL\GenericResolver;
use App\Models\ProcessOperation;
use App\Services\ProcessOperationService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ProcessOperationResolver extends GenericResolver
{
    use HasUpdateOrderMethodTrait;

    public function __construct()
    {
        parent::__construct('process/process', ProcessOperation::class, 'id', true);
    }

    protected function createServiceInstance()
    {
        return ProcessOperationService::instance();
    }

     /**
     * Resolve Stats
     *
     * @param ProcessOperation        $process
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $info
     *
     * @return \GraphQL\Deferred
     */
    public function stats(ProcessOperation $process, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        $dataloader = BatchLoader::instance(
            ProcessOperationStatsLoader::class,
            $info->path,
            [
                'builder'  => \App\Models\ProcessOperation::query(),
            ]
        );
        
        return $dataloader->load(
            $process->getKey(),
            ['parent' => $process]
        );
    }
}
