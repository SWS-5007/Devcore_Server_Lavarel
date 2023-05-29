<?php

namespace App\GraphQL\Resolvers;

use App\Lib\GraphQL\GenericResolver;
use App\Models\IdeaIssue;
use App\Models\Issue;
use App\Models\IdeaIssueReply;
use App\Services\IdeaIssueReplyService;
use App\Services\IssueService;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class IdeaIssueReplyResolver extends GenericResolver
{
    public function __construct()
    {
        parent::__construct('improve/idea', IdeaIssueReply::class, 'id', true);
    }

    protected function createServiceInstance()
    {
        return IdeaIssueReplyService::instance();
    }

    public function listMyFeedback($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $args = collect($args);
        if ($this->canOrFail('read')) {
            $filter = $this->parsePaginatedFilter($args);
            $list = $filter->paginate($this->getServiceInstance()->listUserFeedback($filter));
            return $this->constructPage($list);
        }
    }



    public function deleteMany($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->deleteMany($args);
    }

//    public function findPaginatedReplies($root, array $args,GraphQLContext $context, ResolveInfo $info){
//
//        return IdeaIssueReply::query()
//            ->when($args, function ($query, $filter) use($context){
//                Log::info($filter);
//                return $query->where(
//                    [
//                        'author_id'=> $context->user->id,
//                        'status' => IdeaIssueReply::TYPES[0]
//                    ]
//                );
//            });
//    }


}
