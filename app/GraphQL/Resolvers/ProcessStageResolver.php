<?php

namespace App\GraphQL\Resolvers;

use App\GraphQL\Loaders\ProcessStageRolesWithChildLoader;
use App\GraphQL\Loaders\ProcessStageStatsLoader;
use App\Lib\GraphQL\GenericResolver;
use App\Models\ProcessStage;
use App\Services\ProcessStageService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ProcessStageResolver extends GenericResolver
{
    use HasUpdateOrderMethodTrait;

    public function __construct()
    {
        parent::__construct('process/process', ProcessStage::class, 'id', true);
    }

    protected function createServiceInstance()
    {
        return ProcessStageService::instance();
    }
    /**
     * Resolve Stats
     *
     * @param ProcessStage        $processStage
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $info
     *
     * @return \GraphQL\Deferred
     */
    public function companyRolesWithChild(ProcessStage $processStage, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        $dataloader = BatchLoader::instance(
            ProcessStageRolesWithChildLoader::class,
            $info->path,
            [
                'builder'  => \App\Models\ProcessStage::query(),
            ]
        );

        return $dataloader->load(
            $processStage->getKey(),
            ['parent' => $processStage]
        );
    }



    /**
     * Resolve Stats
     *
     * @param ProcessStage        $processStage
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $info
     *
     * @return \GraphQL\Deferred
     */
    public function stats(ProcessStage $processStage, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        $dataloader = BatchLoader::instance(
            ProcessStageStatsLoader::class,
            $info->path,
            [
                'builder'  => \App\Models\ProcessStage::query(),
            ]
        );

        return $dataloader->load(
            $processStage->getKey(),
            ['parent' => $processStage]
        );
    }
}
