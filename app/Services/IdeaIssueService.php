<?php

namespace App\Services;

use App\Lib\Models\Resources\Resource;
use App\Models\IdeaIssue;
use App\Validators\IdeaIssueValidator;
use App\Validators\ProcessValidator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IdeaIssueService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(IdeaIssue::class, false);
    }


    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    protected function saveFile($instance, $data)
    {

        $col = collect($data);



        if ($col->get('remove_file')) {
            //delete other resources
            foreach ($instance->resources as $resource) {
                $resource->delete();
            }
        }
        if ($col->get('file')) {
            //delete other resources
            foreach ($instance->resources as $resource) {
                $resource->delete();
            }
            $resource = $instance->resources()->create([
                'type' => 'file',
                'display_type' => 'default',
                'owner_type' => 'idea',
                'mime_type' => null,
                'owner_id' => $instance->id,
                'title' => $col->get('file')->getClientOriginalName(),
            ]);
            $resource->saveFile($col->get('file'));
            $resource->save();
        }
        return $instance;
    }

    protected function updateVersion($data, $object)
    {
        if ($object->source_id) {
            $source = $this->findByPrimaryKey($object->source_id);
            $source->version++;
            $source->save();

            $object->title = $object->title . " ({$source->version})";
            $object->save();
        }
    }

    protected function fillFromArray($option, $data, $instance)
    {
        $data = collect($data);
        $instance->title = $data->get('title', $instance->title);
        $instance->description = $data->get('description', $instance->description);
        $instance->process_id = $data->get('process_id', $instance->process_id);
        $instance->project_id = $data->get('project_id', $instance->project_id);
        $instance->project_stage_id = $data->get('project_stage_id', $instance->project_stage_id);
        $instance->type = $data->get('type', $instance->type);

        $instance->parent_id = $data->get('parent_id', $instance->parent_id);
        $instance->parent_type = 'project_idea';

        $instance->anonymous_idea = false;

        if ($data->get('anonymous_idea')) {
            $instance->anonymous_idea = $data->get('anonymous_idea', $instance->anonymous_idea);
        }
        //$instance->parent_type = $data->get('parent_type', $instance->parent_type);
        $parent = $instance->parent;
        if (!$parent) {
            throw new NotFoundHttpException();
        }

        $instance->idea_id = $instance->parent->idea_id;

        return $instance;
    }

    protected function getValidator($data, $object, $option)
    {
        return new IdeaIssueValidator($data, $object, $option);
    }

    protected function created($data, $object)
    {
        $object = $this->saveFile($object, $data);

        $this->updateVersion($data, $object);

        return $object;
    }

    protected function updated($data, $object)
    {
        $object = $this->saveFile($object, $data);

        return $object;
    }

    public function ideaImprovementDelete($data){
        $input = collect($data);
        $object = $this->findByPrimaryKey($input->get('id'));
        $object->delete();
        return $object;
    }


    public function changeStatus($data)
    {
        $input = collect($data);
        $entity = $this->findByPrimaryKey($input->get('id'));
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        $entity->status = $input->get('status');
        $entity->save();
        return $entity;
    }
}
