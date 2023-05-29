<?php

namespace App\Services;

use App\Lib\Models\HasPropertiesColumnTrait;
use App\Lib\Models\Resources\HasResourcesTrait;
use App\Lib\Models\Resources\Resource;
use App\Models\HasCompanyTrait;
use App\Models\HasUserTrait;
use App\Models\Idea;
use App\Models\IssueEffect;
use App\Models\IssueEffectTemplate;
use App\Models\IdeaIssueReply;
use App\Validators\IssueEffectTemplateValidator;
use App\Validators\IssueEffectValidator;
use App\Validators\IdeaIssueReplyValidator;
use App\Validators\ProcessValidator;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IdeaIssueReplyService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(IdeaIssueReply::class, false);
    }


    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }


    protected function fillFromArray($option, $data, $instance)
    {
        $data = collect($data);

        $instance->description = $data->get('description', $instance->description);
        $instance->status = $data->get('status', $instance->status);
        $instance->author_id = $data->get('author_id', $instance->author_id);
        $instance->type_author_id = $data->get('type_author_id', $instance->type_author_id);

        if($data->get('issue_id')){
            $instance->type = "ISSUE";
            $instance->issue_id = $data->get('issue_id', $instance->issue_id);
        }

        if($data->get('idea_id')){
            $instance->type = "IDEA";
            $instance->idea_id = $data->get('idea_id', $instance->idea_id);
        }

        if($data->get('idea_issue_id')){
            $instance->type = "IDEAISSUE";
            $instance->idea_issue_id = $data->get('idea_issue_id', $instance->idea_issue_id);

        }


        return $instance;
    }

    protected function created($data, $instance){
        $data = collect($data);
        $isFeedbackType = $instance->status === IdeaIssueReply::TYPES[0];
        if($instance->type==="ISSUE"){
                if($isFeedbackType){

                    $instance->issue->author->increaseEngagementScore($instance->author_id,$instance->id,$data->get("value"));
                    $instance->issue->replied = true;
                    $instance->type_author_id = $instance->issue->author_id;
                }

                $instance->issue->save();
            } else if($instance->type === "IDEA"){
                if($isFeedbackType){
                    $instance->idea->author->increaseEngagementScore($instance->author_id,$instance->id,$data->get("value"));
                    $instance->idea->replied = true;
                    $instance->type_author_id = $instance->idea->author_id;
                }
                $instance->idea->save();
            } else if($instance->type === "IDEAISSUE"){
                if($isFeedbackType){
                    $instance->idea_issue->author->increaseEngagementScore($instance->author_id,$instance->id,$data->get("value"));
                    $instance->idea_issue->replied = true;
                    $instance->type_author_id = $instance->idea_issue->author_id;
                }
                $instance->idea_issue->save();
            }

        }

    function listUserFeedback($filter = null, $order = null)
    {
        if ($order) {
            $filter->parseSortStr($order);
        }
        if ($filter) {
            $query = IdeaIssueReply::where(
                [
                    "company_id" => $this->getUser()->company_id,
                    "status" => IdeaIssueReply::TYPES[0],
                    "type_author_id" => $this->getUser()->id
                ])
                ->has("score_instance")->orderBy("created_at", "DESC")->orderBy("created_at", "DESC");

            return $filter->apply($query);
        }
        return $this->getBaseQuery();
    }
    protected function getValidator($data, $object, $option)
    {
        return new IdeaIssueReplyValidator($data, $object, $option);
    }

    protected function deleted($object){
        return $object;
    }
}
