<?php

namespace App\Lib\Filter;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CursorPaginatedFilter extends Filter{

    public $first = 5;
    public $after = null;

    public function parseArgs(Collection $args)
    {

        parent::parseArgs($args);
        $this->first = $args->get('first', $this->first);
        $this->after = $args->get('after', $this->after);
    }

    public function paginate($builder, $first = null, $after = null, $select = ['*'])
    {
        $this->first = $first ? $first : $this->first;
        $this->after = $after ? $after : $this->after;

        return parent::paginate($builder, $this->first, $this->after, $select);
    }
}
