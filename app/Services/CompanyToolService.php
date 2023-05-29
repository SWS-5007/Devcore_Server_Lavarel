<?php

namespace App\Services;

use App\Lib\Validators\GenericValidator;
use App\Models\Company;
use App\Models\CompanyTool;
use App\Models\CompanyToolPrice;
use App\Models\Tool;
use App\Validators\CompanyToolValidator;

class CompanyToolService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {

        parent::__construct(CompanyTool::class, false);
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
        $type = "TOOL";


        if (!$data->get('tool_id')) {
            $toolId = null;
            if ($data->get('company_tool_id')) {
                $type = "MODULE";
                $toolId = $this->findByPrimaryKey($data->get('company_tool_id'))->tool_id;
            }
            $tool = ToolService::instance()->create(['name' => $data->get('name'), 'tool_id' => $toolId, 'type' => $type]);
        } else {
            $tool = ToolService::instance()->findByPrimaryKey($data->get('tool_id'));
        }
        $instance->tool_id = $tool->id;

        if ($data->get('company_tool_id')) {
            $instance->parent_id = $data->get('company_tool_id');
        }

        return $instance;
    }

    protected function setPrice($data, $object)
    {
        $input = collect($data);
        // dd($input);
        $price = new CompanyToolPrice();
        $price->company_tool_id = $object->id;
        $price->tool_id = $object->tool_id;
        $price->price_model = $input->get('price_model');
        $price->price = $input->get('yearly_costs');
        $price->save();
    }

    protected function saveGeneralTool($object)
    {
    }

    protected function created($data, $object)
    {

        //save the tool in the public table
        $this->saveGeneralTool($object);
        //if the tool is a module
        // if ($object->parent_id) {
        //     $this->setPrice($data, $object);
        // }

        return $object;
    }


    protected function updated($data, $object)
    {

        //save the tool in the public table
        $this->saveGeneralTool($object);
        // dd($object);
        // dd($object);
        //if the tool is a module
        if ($object->parent_id) {
            $this->setPrice($data, $object);
        }

        return $object;
    }


    protected function getValidator($data, $object, $option)
    {
        return new CompanyToolValidator($data, $object, $option);
    }

    public function deleted($object){
        $data = collect($object);

        //Delete Tool
        $object->tool()->delete();

        //Delete related modules
       $toolModules = ToolService::instance()->find()->where('tool_id',$data->get('tool_id'));
       $toolModules->delete();

        return $object;
    }

    public function changeStatus($input)
    {
        $data = collect($input);
        $toolModule = $this->findByPrimaryKey($data->get('id'));
        $toolModule->status = $data->get('status');
        $toolModule->save();
        return $toolModule;
    }


    public function updateTool($input)
    {
        $data = collect($input);
        $toolToUpdate = null;
        if ($data->get('tool_id')) {
            $toolToUpdate = ToolService::instance()->findByPrimaryKey($data->get('tool_id'));
        } else {
            $toolToUpdate = ToolService::instance()->findByPrimaryKey($data->get('id'));
        }

        $toolToUpdate->name = $data->get('name');
        //get instance that belongs to updated tool
        $instance = $this->find()->where('id', $data->get('id'))->first();

        //associate updated belongsTo relationship
       $companyTool = $instance->tool()->associate($toolToUpdate);

       //saves the tool and its relations
        $companyTool->push();


        return $companyTool;
    }
}
