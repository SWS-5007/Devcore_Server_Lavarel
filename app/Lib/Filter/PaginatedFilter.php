<?php

namespace App\Lib\Filter;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PaginatedFilter extends Filter
{
    public $pageSize = 10;
    public $page = 1;

    public function parseArgs(Collection $args)
    {
        parent::parseArgs($args);
        $this->pageSize = $args->get('perPage', $this->pageSize);
        $this->page = $args->get('page', $this->page);
    }

    public function paginate($builder, $pageSize = null, $page = null, $select = ['*'])
    {
        $this->pageSize = $pageSize ? $pageSize : $this->pageSize;
        $this->page = $page ? $page : $this->page;

        return parent::paginate($builder, $this->pageSize, $this->page, $select);
    }
}
