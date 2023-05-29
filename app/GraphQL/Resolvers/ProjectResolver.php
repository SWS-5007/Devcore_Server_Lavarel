<?php

namespace App\GraphQL\Resolvers;

use App\GraphQL\Loaders\ProjectStatsLoader;
use App\Lib\Context;
use App\Lib\GraphQL\GenericResolver;
use App\Lib\Models\Notification;
use App\Models\Project;
use App\Models\ProjectEvaluationRecord;
use App\Notifications\ProjectCreated;
use App\Notifications\ProjectNextStage;
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


class ProjectResolver extends GenericResolver
{
    public function __construct()
    {
        parent::__construct('core/project', Project::class, 'id', true);
    }

    /**
     * @return ProjectService
     */
    protected function createServiceInstance()
    {
        return ProjectService::instance();
    }

    public function listMyProjectsAll($root, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        if ($this->canOrFail('read')) {
            $list = $this->getServiceInstance()->listMyProjects($this->parseFilter($args))->where('status', 'STARTED');
            return $this->constructOutputList($list->get());
        }
    }

    public function listMyProjects($root, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        $args = collect($args);
        if ($this->canOrFail('read')) {
            $filter = $this->parseFilter($args);
            $list = $filter->paginate($this->getServiceInstance()->listMyProjects($filter))->where('status', 'STARTED');
            return $this->constructPage($list);
        }
    }

    public function evaluateIdea($root, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        $instance = ProjectEvaluationRecord::find($args['input']['id']);
        if ($this->canOrFail('evaluateIdea', $instance)) {
            return $this->getServiceInstance()->evaluateIdea($args['input'], $instance);
        }
    }

    protected function notifyProjectUpdate($project)
    {
        \Nuwave\Lighthouse\Execution\Utils\Subscription::broadcast('projectUpdated', $project);
    }

    protected function notifyProjectUsers($project,$notification){

        if($project!=null):

            foreach($project->users as $user):
                try {

                        $user->notify($notification);

                } catch(CouldNotSendNotification $ex) {
                    $msg = " User - id: ".$user->id." , email: ".$user->email." :: ".$ex->getMessage();
                    Log::error($msg);
                }
            endforeach;
        endif;
    }

    protected function afterCreate($object, $output)
    {

        $this->notifyProjectUpdate($object);
        $this->notifyProjectUsers($object,new ProjectCreated($object));
        return $output;
    }

    protected function afterUpdate($object, $output)
    {
        $this->notifyProjectUpdate($object);
        return $output;
    }

    /**
     * Next stage
     *
     * @param Project        $project
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $info
     *
     * @return \GraphQL\Deferred
     */
    public function nextStage($root, array $args, GraphQLContext $context, ResolveInfo $info)
    {

        $project = $this->createServiceInstance()->findByPrimaryKey($args['id']);

        $this->canOrFail('manage', $project);

        $ret = $this->createServiceInstance()->nextStage($project);
        $this->notifyProjectUsers($project,new ProjectNextStage($project));
        $this->notifyProjectUpdate($project);
        // return $project;
        return $ret;
    }




    /**
     * Next stage
     *
     * @param Project        $project
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $info
     *
     * @return \GraphQL\Deferred
     */
    public function completeStage($root, array $args, GraphQLContext $context, ResolveInfo $info)
    {

        $project = $this->createServiceInstance()->findByPrimaryKey($args['id']);
        $this->canOrFail('manage', $project);

        $stages = $project->stages()->whereIn('id', $args['stageId'])->get();
        foreach ($stages as $stage){
            $ret = $this->createServiceInstance()->completeStage($stage);
        }
        $this->notifyProjectUpdate($project);
        return $ret;
    }


    /**
     * Resolve pending evaluations of project
     *
     * @param Project        $project
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $info
     *
     * @return \GraphQL\Deferred
     */
    public function getPendingEvaluations(Project $project, array $args, GraphQLContext $context, ResolveInfo $info)
    {

        if ($context->user()) {
            if ($context->user()->can($this->constructCompleteOperation('manage'), $project)) {
                return $project->pendingEvaluations($context->user())->get();
            }
        }
        return [];
    }


    /**
     * Resolve pending evaluations of project
     *
     * @param Project        $project
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $info
     *
     * @return \GraphQL\Deferred
     */
    public function getUserPendingEvaluations(Project $project, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        if ($context->user()) {
            return $project->userPendingEvaluations($context->user())->get();
        }
        return [];
    }

    /**
     * Resolve ideas for user of project
     *
     * @param Project        $project
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $info
     *
     * @return \GraphQL\Deferred
     */
    public function getUserIdeas(Project $project, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        if ($context->user()) {
            return $project->userIdeas($context->user())->get();
        }
        return [];
    }

    /**
     * Resolve Stats
     *
     * @param Project        $project
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $info
     *
     * @return \GraphQL\Deferred
     */
    public function stats(Project $project, array $args, GraphQLContext $context, ResolveInfo $info)
    {

        $dataloader = BatchLoader::instance(
            ProjectStatsLoader::class,
            $info->path,
            [
                'builder'  => \App\Models\Project::query(),
            ]
        );
        $result =  $dataloader->load(
            $project->getKey(),

            ['parent' => $project]);

        return $result;
    }
}
