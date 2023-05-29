<?php

namespace App\GraphQL\Resolvers;

use App\GraphQL\Loaders\ProjectStatsLoader;
use App\Lib\Context;
use App\Lib\GraphQL\GenericResolver;
use App\Models\ShareLink;
use App\Services\ShareLinkService;
use App\Services\ProjectService;

use App\Services\UserService;
use App\UserDevice;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use Illuminate\Support\Facades\Log;
use NotificationChannels\Fcm\Exceptions\CouldNotSendNotification;
use Carbon\Carbon;


class ShareLinkResolver extends GenericResolver
{
    public function __construct()
    {
        parent::__construct('core/sharelink', ShareLink::class, 'id', false);
    }

    /**
     * @return ShareLinkService
     */
    protected function createServiceInstance()
    {
        return ShareLinkService::instance();
    }

    public function shareProjectByLinkId($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $link = ShareLink::where('id', $args['id'])->first();

        if ($link){
            // Assign User to Project
            $user = auth()->user();
            $projectId = $link->project_id;
            ProjectService::instance()->addUser($projectId, $user->id);
            return true;
        }
        else
            return false;
    }

    public function getShareLinkByHash($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $link = ShareLink::where('url_hash', $args['urlHash'])->first();

        return $link;
    }

}
