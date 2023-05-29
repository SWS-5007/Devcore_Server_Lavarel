<?php

namespace App\Lib\Filter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class IncludeFilter extends Filter
{
    public $field;
    public $as;

    public function __construct($args = [])
    {
        parent::__construct($args);
        $this->field= $this->args->get('field');
        $this->as= $this->args->get('as');
    }
}