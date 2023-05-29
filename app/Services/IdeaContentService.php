<?php

namespace App\Services;

use App\Lib\Models\Resources\Resource;
use App\Lib\Services\GenericService;
use App\Models\Idea;
use App\Models\IdeaContent;
use App\Validators\IdeaContentValidator;
use App\Validators\IdeaValidator;
use App\Validators\ProcessValidator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use Tiptap\Editor;

class IdeaContentService extends GenericService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(IdeaContent::class, false);
    }


    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }


    protected function deleted($object){

        return $object;
    }

    protected function syncRoles($data, $instance)
    {
        $data = collect($data);
        $instance->syncCompanyRoles($data->get('company_roles', []));
        return $instance;
    }

    protected function updated($data, $object){
        $data = collect($data);
        $object->is_primary = $data->get('is_primary', false);
        if ($object->is_primary) {
            $this->syncIdea($object);
        }
        $object = $this->syncRoles($data, $object);
        return $object;
    }

    protected function created($data, $object){
        $data = collect($data);
        $object->is_primary = $data->get('is_primary', false);
        if ($object->is_primary) {
            $this->syncIdea($object);
        }
        $object = $this->syncRoles($data, $object);
        return $object;
    }


    public function syncIdea($object)
    {
        $ideaToUpdate = IdeaService::instance()->findByPrimaryKey($object->idea_id);

        if (!$ideaToUpdate) {
            throw new NotFoundHttpException();
        }
        $ideaToUpdate->content_id = $object->id;
        // needs to set all

        $ideaToUpdate->save();

        return $object;
    }

    // Editor from tiptap-php namespace
//    public function sanitizeContent($markup) {
//
//        $editor = new Editor();
//        $content = json_decode(json_encode($markup), true);
//
//        return $editor->sanitize($content);
//    }

    protected function fillFromArray($option, $data, $instance)
    {
        $data = collect($data);
        $instance->idea_id = $data->get('idea_id', $instance->idea_id);
        $instance->version = $data->get('version', $instance->version);
        $instance->content_type = $data->get('content_type', $instance->content_type);

        try {
            $instance->markup = $data->get('markup', $instance->markup);
            //$instance->markup = $this->sanitizeContent($data->get('markup', $instance->markup));
        } catch (exception $e) {
            Log::info($e);
            throw new \Exception(trans('messages.ideaContentSanitizeFail'));
        }

        return $instance;
    }


    protected function getValidator($data, $object, $option)
    {
        return new IdeaContentValidator($data, $object, $option);
    }

    function listPermissionedObjects($filter = null, $order = null)
    {
        if ($order) {
            $filter->parseSortStr($order);
        }

        if ($filter) {
            return $filter->apply($this->getBaseQuery());
        }
        return $this->getBaseQuery('companyRoles');
    }

}
