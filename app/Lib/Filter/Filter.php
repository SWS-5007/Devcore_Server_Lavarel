<?php

namespace App\Lib\Filter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class Filter
{
    public $where = null;
    public $with = [];
    public $orderBy = null;
    public $args = null;
    public $fieldAliases = [];

    public function __construct($args = [], $fieldAliases = null)
    {
        $this->fieldAliases = $fieldAliases ? $fieldAliases : $this->fieldAliases;
        if ($args instanceof Collection) {
            $this->parseArgs($args);
        } else {
            $this->parseArgsFromArr($args);
        }
    }

    public function parseArgs(Collection $args)
    {
        $this->args = $args;

        if ($args->has('where')) {
            $this->where = new FilterCondition($args->get('where'));
        }
        if ($args->has('orderBy')) {
            $this->orderBy = [];
            foreach ($args->get('orderBy') as $sort) {

                $this->orderBy[] = (new OrderBy())->fromString($sort);
            }
        }
        if ($args->has('with')) {
            $with = $args->get('with');
            foreach ($with as $w) {
                $this->with[] = IncludeFilter::fromArgs($w);
            }
        }
    }



    public function parseArgsFromArr(array $args = [])
    {
        return $this->parseArgs(collect($args));
    }

    public static function fromRequest(Request $request)
    {
        return static::fromArgs($request->all());
    }

    public static function fromArgs($args)
    {
        return new static($args);
    }


    public function paginate($builder, $pageSize = 50, $page = 1, $select = ['*'])
    {

        return $builder->paginate($pageSize, $select, 'page', $page);
    }


    public function apply($builder)
    {

        if ($this->where instanceof FilterCondition) {
            $this->where->apply($builder);
        }

        if ($this->with && count($this->with)) {
            foreach ($this->with as $w) {
                $builder->with([$w->field => function ($b) use ($w) {
                    $w->apply($b);
                }]);
            }
        }

        if ($this->orderBy && count($this->orderBy)) {
            foreach ($this->orderBy as $order) {
                if (is_callable(array($builder, 'isTranslatableAttribute'))) {
                    try {
                        $builder->orderBytranslationAttribute($order->field, $order->direction);
                    } catch (\Exception $ex) {
                        $builder->orderBy($order->field, $order->direction);
                    }
                }else{
                    $builder->orderBy($order->field, $order->direction);
                }

            }
        }

        return $builder;
    }
}
