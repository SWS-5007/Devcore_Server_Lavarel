<?php

namespace App\Services;

use App\Lib\Validators\GenericValidator;
use App\Models\CompanyTool;
use App\Models\CompanyToolPrice;
use App\Validators\CompanyToolPriceValidator;
use App\Validators\CompanyToolValidator;
use Carbon\Carbon;

class CompanyToolPriceService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(CompanyToolPrice::class, false);
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

        // if (!$data->get('tool_id')) {
        //     $toolId = null;
        //     if ($data->get('company_tool_id')) {
        //         $toolId = $this->findByPrimaryKey($data->get('company_tool_id'))->tool_id;
        //     }
        //     $tool = CompanyToolService::instance()->create(['name' => $data->get('name'), 'tool_id' => $toolId]);
        // } else {
        //     $tool = CompanyToolService::instance()->findByPrimaryKey($data->get('tool_id'));
        // }

        $instance->tool_id = CompanyToolService::instance()->findByPrimaryKey($data->get('company_tool_id'))->tool_id;
        $instance->company_tool_id = $data->get('company_tool_id');
        $instance->name = $data->get('name', '');
        $instance->price = $data->get('price', $instance->price);
        $instance->price_model = $data->get('price_model', $instance->price_model);
        $instance->expiration = $data->get('expiration', $instance->expiration);

        if ($data->get('price_model') == 'PROJECT') {
            $instance->parent_type = "PROJECT";
            $instance->parent_id = $data->get('parent_id');
        }


        return $instance;
    }

    public function deleted($object){
        return $object;
    }


    protected function getValidator($data, $object, $option)
    {
        return new CompanyToolPriceValidator($data, $object, $option);
    }
}
