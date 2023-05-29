<?php

namespace App\Lib\Filter;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderBy
{
    public $field;
    public $direction = "ASC";

    public function __construct($field = null, $direction = 'ASC')
    {
        $this->field = Str::snake($field);
        $this->direction = $direction;
    }

    public function fromString($string)
    {
        $isDesc = strpos($string, '-');
        if ($isDesc === false || $isDesc > 0) {
            $this->field = $string;
        } else {
            $this->direction = "DESC";
            $this->field = substr($string, 1);
        }
        $this->field = Str::snake($this->field);
        return $this;
    }

    public function apply($builder)
    {
        $builder->orderBy($this->field, $this->direction);
    }
}
