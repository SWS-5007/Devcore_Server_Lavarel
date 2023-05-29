<?php

namespace App\Services;

use App\Lib\Models\Resources\Resource;
use App\Models\Issue;
use App\Models\IssueEffect;
use App\Validators\IssueValidator;
use App\Validators\ProcessValidator;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IssueService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(Issue::class, false);
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
                'owner_type' => 'issue',
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

            $object->title = $source->version;
            $object->save();
        }
    }

    protected function fillFromArray($option, $data, $instance)
    {
        $data = collect($data);

      //  $instance->title = $data->get('title', $instance->title);
        $instance->description = $data->get('description', $instance->description);
        $instance->process_id = $data->get('process_id', $instance->process_id);
        $instance->project_id = $data->get('project_id', $instance->project_id);
        $instance->type = $data->get('type', $instance->type);


        $instance->money_value = $data->get('money_value', $instance->money_value ? $instance->money_value : 0);
        $instance->money_unit = "TOTAL"; //money only total value

        $instance->time_unit = $data->get('time_unit', $instance->time_unit ? $instance->time_unit : 'TOTAL');

        $instance->time_value = $data->get('time_value', $instance->time_value ? $instance->time_value : 0);
        $instance->money_unit = $data->get('money_unit', $instance->money_unit);

        $instance->anonymous_idea = false;

        if ($data->get('anonymous_idea')) {
            $instance->anonymous_idea = $data->get('anonymous_idea', $instance->anonymous_idea);
        }
        $instance->checked_issue = false;

        if ($data->get('checked_issue')) {
            $instance->checked_issue = $data->get('checked_issue', $instance->checked_issue);
        }

        if ($data->get('effect_template_id')) {
            $instance->effect_template_id = $data->get('effect_template_id', $instance->effect_template_id);
        }

        $stage = $instance->project->getProjectStageByProcessStage($data->get('stage_id'));
        if (!$stage) {
            throw new BadRequestHttpException('This stage doesn\'t belongs to the project!');
        }

        $instance->project_stage_id = $stage->id;

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

        return $instance;
    }

    protected function getValidator($data, $object, $option)
    {
        return new IssueValidator($data, $object, $option);
    }

    protected function created($data, $object)
    {
        $object = $this->saveFile($object, $data);

        $this->updateVersion($data, $object);

        $this->calculateIssueTotals($object);


        if($object->effect_template_id){
            $this->setTemplate(
                [
                    "id" => $object->id,
                    "effect_template_id" => $object->effect_template_id
                ]);
        }


        return $object;
    }

    protected function updated($data, $object)
    {
        $object = $this->saveFile($object, $data);
        $this->calculateIssueTotals($object);
        return $object;
    }

    public function calculateIssueTotals($object)
    {
        //calculate the money value
        $object->calculateTotals();
        $object->project->calculateTotals();
        $object->projectStage->calculateTotals();
        $object->save();
    }

    public function deleteMany($data)
    {
        $input = collect($data['ids']);
        $input->map(function ($id) {
            $this->delete($id);
        });
    }

    public function deleted($object)
    {

        $effectId = $object->effect_template_id;
        $effect = IssueEffect::where("id", $effectId)->delete();
        return $object;
    }

    public function checkIssue($data)
    {
        $input = collect($data);
        $entity = $this->findByPrimaryKey($input->get('id'));
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        $entity->checked_issue = $input->get('checked_issue');
        $entity->save();
        return $entity;
    }






    public function setTemplate($data)
    {
        $input = collect($data);
        $entity = $this->findByPrimaryKey($input->get('id'));
        $issueActiveId = null;
        $toolToUpdate = null;
        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $masterEffect = IssueEffect::where(
                    [
                        'id' => $input->get('effect_template_id')
                    ])->with('templates')->first();

        $entity->effect()->delete();

        $clone = $masterEffect->replicate();
                $clone->status = 'ACTIVE';
                $clone->effect_id = $masterEffect->id;
                $clone->created_at = $masterEffect->created_at;
                $clone->issue_active_id = $input->get('id');
                $clone->save();

        $relations = $masterEffect->getRelations();
            foreach ($relations as $relation) {
                foreach ($relation as $relationRecord) {
                    $newRelationship = $relationRecord->replicate();
                    $newRelationship->effect_id = $clone->id;
                    $newRelationship->push();
                }
            }

        $entity->effect_template_id = $clone->id;
        $entity->save();

        return $entity;
    }

    public function unsetTemplate($data)
    {
        $input = collect($data);
        $entity = $this->findByPrimaryKey($input->get('id'));
        if (!$entity) {
            throw new NotFoundHttpException();
        }
        $template = IssueEffect::where('id', $input->get('effect_template_id'));

        $entity->effect_template_id = null;

        $entity->save();
        return $entity;
    }

    public function closeIssueFeedback($data)
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
