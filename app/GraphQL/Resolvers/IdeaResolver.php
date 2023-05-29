<?php

namespace App\GraphQL\Resolvers;

use App\GraphQL\Loaders\IdeaStatsLoader;
use App\Lib\GraphQL\GenericResolver;
use App\Models\Idea;
use App\Services\IdeaIssueService;
use App\Services\IdeaService;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class IdeaResolver extends GenericResolver
{
    public function __construct()
    {
        parent::__construct('improve/idea', Idea::class, 'id', true);
    }

    protected function createServiceInstance()
    {
        return IdeaService::instance();
    }

    protected function createIdeaIssueServiceInstance()
    {
        return IdeaIssueService::instance();
    }

    public function changeStatus($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->changeStatus($args['input']);
    }

    public function closeIdeaFeedback($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->closeIdeaFeedback($args);
    }

    public function closeImprovementFeedback($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->closeImprovementFeedback($args['input']);
    }

    public function setIdeaViewed($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->setIdeaViewed($args['input']);
    }

    public function ideaImprovementDelete($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createIdeaIssueServiceInstance()->ideaImprovementDelete($args);
    }

    public function setIdeaResource($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->setIdeaResource($args['input']);
    }

    public function removeIdeaResource($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->removeIdeaResource($args['input']);
    }


    /**
     * Resolve Stats
     *
     * @param Idea        $idea
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $info
     *
     * @return \GraphQL\Deferred
     */
    public function stats(Idea $idea, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        $dataloader = BatchLoader::instance(
            IdeaStatsLoader::class,
            $info->path,
            [
                'builder'  => \App\Models\Idea::query(),
            ]
        );

        return $dataloader->load(
            $idea->getKey(),
            ['parent' => $idea]
        );
    }

}
