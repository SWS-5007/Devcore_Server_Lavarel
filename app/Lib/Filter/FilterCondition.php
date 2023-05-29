<?php

namespace App\Lib\Filter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class FilterCondition
{
    const OPERATORS = [
        'eq' => '=',
        'in' => 'IN',
        'nin' => "NOT_IN",
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'bw' => "BETWEEN",
        'nbw' => "NOT_BETWEEN",
        'isnull' => "IS_NULL",
        'notnull' => "NOT_NULL",
        'like' => "LIKE",
        'nlike' => "NOT LIKE",
        'cn' => "CONTAINS",
        'ncn' => "NOT_CONTAINS",
        'sw' => "STARTS_WITH",
        'nsw' => "NOT_STARTS_WITH",
        'ew' => "ENDS_WITH",
        'new' => "NOT_ENDS_WITH",
    ];

    public $field;
    public $op = 'eq';
    public $value;
    public $and = [];
    public $or = [];
    public $not = [];
    public $fieldAliases = [];

    public function __construct($args = [], $fieldAliases = null)
    {
        $this->fieldAliases = $fieldAliases ? $fieldAliases : $this->fieldAliases;
        $this->parseArgsArr($args);
    }

    protected function parseArgs(Collection $args)
    {

        $this->field = $args->get('field', null);
        $this->op = $args->get('op', 'eq');
        $this->value = $args->get('value', null);

        $andConditions = $args->get('and', []);
        $orConditions = $args->get('or', []);



        if (count($andConditions)) {
            foreach ($andConditions as $c) {
                $this->and[] = new FilterCondition($c);
            }
        }

        if (count($orConditions)) {
            foreach ($orConditions as $c) {
                $this->or[] = new FilterCondition($c);
            }
        }
    }

    protected function parseArgsArr(array $args = [])
    {
        return $this->parseArgs(collect($args));
    }


    public function apply($builder)
    {

        //single
        if ($this->isFieldCondition()) {
            $builder->where(function () use ($builder) {
                $this->applyCondition($builder);
            });
        }


        //and
        if ($this->and && is_array($this->and) && count($this->and)) {
            $builder->where(function ($query) use ($builder) {
                foreach ($this->and as $c) {
                    $this->applyGroup($query, $c);
                }
            });
        }

        //or
        if ($this->or && is_array($this->or) && count($this->or)) {
            $builder->orWhere(function ($query) use ($builder) {
                foreach ($this->or as $c) {
                    $query->orWhere(function ($q) use ($c) {
                        $this->applyGroup($q, $c);
                    });
                }
            });
        }

        return $builder;
    }

    public function isFieldCondition(): bool
    {
        return $this->field && $this->op;
    }

    protected function applyGroup($builder, FilterCondition $condition)
    {
        return $condition->apply($builder);

    }

    protected function applyCondition($builder)
    {
        $field = $this->parseField();
        
        if (is_callable(array($builder, 'isTranslatableAttribute'))) {
            try {
                $overrideField = $builder->getTranslationFieldForQuery($field);
                if ($overrideField) {
                    $field = $overrideField;
                }
            } catch (\Exception $ex) {
            }
        }
        $operator = $this->parseOperator();

        switch ($operator) {
            case "IN":
                $builder->whereIn($field, $this->parseValue());
                break;
            case "NOT_IN":
                $builder->whereNotIn($field, $this->parseValue());
                break;
            case "BETWEEN":
                $builder->whereBetween($field, $this->parseValue());
                break;
            case "NOT_BETWEEN":
                $builder->whereNotBetween($field, $this->parseValue());
                break;
            case "IS_NULL":
                $builder->whereNull($field);
                break;
            case "NOT_NULL":
                $builder->whereNotNull($field);
                break;
            case "STARTS_WITH":
                $builder->where($field, 'LIKE', $this->parseValue() . '%');
                break;
            case "NOT_STARTS_WITH":
                $builder->where($field, 'NOT LIKE', $this->parseValue() . '%');
                break;
            case "ENDS_WITH":
                $builder->where($field, 'LIKE', '%' . $this->parseValue());
                break;
            case "NOT_ENDS_WITH":
                $builder->where($field, 'NOT LIKE', '%' . $this->parseValue());
                break;
            case "CONTAINS":
                $builder->where($field, 'LIKE', '%' . $this->parseValue() . '%');
                break;
            case "NOT_CONTAINS":
                $builder->where($field, 'NOT LIKE', '%' . $this->parseValue() . '%');
                break;
            default:
                $builder->where($field, $operator, $this->parseValue());
        }
    }

    protected function parseField()
    {
        return $this->field;
    }

    protected function parseOperator()
    {
        return self::OPERATORS[$this->op];
    }

    protected function parseValue()
    {
        return $this->value;
    }
}
