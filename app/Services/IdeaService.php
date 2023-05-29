<?php

namespace App\Services;

use App\Lib\Models\Resources\Resource;
use App\Models\Idea;
use App\Validators\IdeaValidator;
use App\Validators\ProcessValidator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IdeaService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(Idea::class, false);
    }


    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    protected function syncCompanyTools($data, $instance)
    {
        $data = collect($data);
        $instance->syncCompanyTools($data->get('company_tool_ids', []));
        return $instance;
    }

//    public function removeFiles($instance, $data)
//    {
//
//        $col = collect($data);
//        $removeResources = false;
//        if ($col->get('remove_file')) {
//            $removeResources = $instance->resources;
//        }
//        if ($col->get('remove_file_ids')) {
//            $removeResources = $instance->resources->whereIn('uri', $col->get('remove_file_ids'));
//        }
//
//        if($removeResources) {
//            if(count($removeResources) > 0){
//                foreach ($removeResources as $resource) {
//                    $resource->delete();
//                }
//            }
//        }
//
//        return $instance;
//    }

    protected function updateVersion($data, $object)
    {
        if ($object->source_id) {
            $sourceIdea = $object->source->idea;
            $sourceIdea->version++;
            $sourceIdea->save();
            // $object->title = $object->title . " ({$source->version})";
            // $object->save();
        }
    }



    protected function syncRoles($data, $instance)
    {
        $data = collect($data);
        $instance->syncCompanyRoles($data->get('company_role_ids', []));

        return $instance;
    }

    protected function deleted($object){
        return $object;
    }

    protected function fillFromArray($option, $data, $instance)
    {
        $data = collect($data);
        $instance->title = $data->get('title', $instance->title);
        $instance->description = $data->get('description', $instance->description);
        $instance->process_id = $data->get('process_id', $instance->process_id);
        $instance->type = $data->get('type', $instance->type);
        $instance->anonymous_idea = false;
        $instance->properties = null;

        if($data->get('company_role_ids')) {
            $instance->company_role_ids = $data->get('company_role_ids', $instance->company_role_ids);
        } else {
            $instance->company_role_ids = [];
        }

        if($data->get('company_tool_ids')) {
            $instance->company_tool_ids = $data->get('company_tool_ids', $instance->company_tool_ids);
        } else {
            $instance->company_tool_ids = [];
        }

        if ($data->get('anonymous_idea')) {
            $instance->anonymous_idea = $data->get('anonymous_idea', $instance->anonymous_idea);
        }
        if ($data->get('source_id')) {
         //   $instance->version = $data->get('version', $instance->version);
            $instance->source_id = $data->get('source_id', $instance->source_id);
            $instance->source_type = $data->get('source_type', $instance->source_type);
        }

        //parent
        if (!$data->get('operation_id')) {
            $instance->parent_type = "process_stage";
            $instance->parent_id = $data->get('stage_id');
        } else if (!$data->get('phase_id')) {
            $instance->parent_type = "process_operation";
            $instance->parent_id = $data->get('operation_id');
        } else {
            $instance->parent_type = "process_phase";
            $instance->parent_id = $data->get('phase_id');
        }
        $parent = $instance->parent;
        if (!$parent) {
            throw new NotFoundHttpException();
        }



      //  if ($data->get('type') === 'TOOL') {
            $instance->company_tool_id = $data->get('company_tool_id', $instance->company_tool_id);
     //   }

        return $instance;
    }

    protected function getValidator($data, $object, $option)
    {
        return new IdeaValidator($data, $object, $option);
    }

    protected function beforeCreate($data, $object)
    {
        //$object->disableVersioning();
        return $object;
    }

    protected function beforeDelete($object)
    {
        $object->title = $object->title.'-'.$object->updated_at;
        $object->save();
        return $object;
    }

    protected function created($data, $object)
    {
        // $object = $this->removeFiles($object, $data);
        //$object->enableVersioning();
        $this->updateVersion($data, $object);
        $object = $this->syncRoles($data, $object);
        $object = $this->syncCompanyTools($data, $object);
        return $object;
    }

    protected function updated($data, $object)
    {
      //  $object = $this->removeFiles($object, $data);
        $object = $this->syncRoles($data, $object);
        $object = $this->syncCompanyTools($data, $object);
        //$object->enableVersioning();
        return $object;
    }

    protected function beforeUpdate($data, $object)
    {
        //$object->disableVersioning();
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

    public function closeIdeaFeedback($data)
    {
        $input = collect($data);
        $entity = $this->findByPrimaryKey($input->get('id'));
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        $entity->replied = true;
        $entity->save();
        return $entity;
    }

    public function setIdeaViewed($data)
    {
        $input = collect($data);
        $entity = $this->findByPrimaryKey($input->get('id'));
        if (!$entity) {
            throw new NotFoundHttpException();
        }

        if($entity->properties){
            $userIds = $entity->properties;
         if(!in_array($input->get('id'), $userIds)){
                $userIds[] = $this->getUser()->id;
                $entity->properties = $userIds;
                $entity->save();
           }
        } else {
            $entity->properties = array($this->getUser()->id);
            $entity->save();
        }

        return $entity;
    }

    public function removeIdeaResource($data) {
        $input = collect($data);

        $removeResources = false;
        $entity = $this->findByPrimaryKey($input->get('id'));
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        if ($input->get('remove_file_ids')) {
            $removeResources = $entity->resources->whereIn('uri', $input->get('remove_file_ids'));
        }

        if($removeResources) {
            if(count($removeResources) > 0){
                foreach ($removeResources as $resource) {
                    if ($resource->properties) {
                        Log::info(sprintf('Removing resource %s... idea: %s...', $resource->title, $entity->title));
                        $resource->delete();
                    }
                }
            }
        }
        return $entity;
    }

    public function setIdeaResource($data) {
        $input = collect($data);
        $resources = [];

        $entity = $this->findByPrimaryKey($input->get('id'));
        if (!$entity) {
            throw new NotFoundHttpException();
        }

        if ($input->get('files')) {

            foreach ($input->get('files') as $fileResource) {

                $file = $fileResource['file'];

                $resourceEntity = [
                    'type' => 'file',
                    'display_type' => 'default',
                    'owner_type' => 'idea',
                    'mime_type' => null,
                    'properties' => [
                        'content_key' => $fileResource['uuid']
                    ],
                    'title' => $file->getClientOriginalName()
                ];

                $resource = $entity->resources()->create($resourceEntity);
                $resources[] = $resourceEntity;
                $resource->saveFile($file);
                $resource->save();
            }
        }

//        if ($fileEntity) {
//            foreach ($fileEntity->files as $fileEntry) {
//                Log::info("entity: ", [json_encode($fileEntity)]);
//
//                    Log::info("resource: ", [$fileEntry['uuid']]);
//
//                    $resource = $entity->resources()->create([
//                        'type' => 'file',
//                        'display_type' => 'default',
//                        'owner_type' => 'idea',
//                        'mime_type' => null,
//                        'properties' => $fileEntry['uuid'],
//                        'owner_id' => $entity->id,
//                        'title' => $fileEntry->file->getClientOriginalName()
//                    ]);
//                    //$resource->setFileDisk("s3");
//                    $resource->saveFile($fileEntry->file);
//                    $resource->save();
//            }
//        }

        return $entity;
    }

    public function setIdeaNotViewed($data)
    {
        $input = collect($data);
        $entity = $this->findByPrimaryKey($input->get('id'));
        if (!$entity) {
            throw new NotFoundHttpException();
        }

        if($entity->properties){
          $entity->properties = null;
          $entity->properties->save();
        }

        return $entity;
    }


    public function closeImprovementFeedback($data)
    {
        $input = collect($data);

        $entity = $this->findByPrimaryKey($input->get('id'));
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        $ideaIssues = $entity->idea_issues->whereIn("id", $input->get("improvement_id"));
        foreach($ideaIssues as $ideaIssue) {
            $ideaIssue->replied = true;
            $ideaIssue->save();
        }

        return $entity;
    }
}
