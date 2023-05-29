<?php

namespace App\Lib\GraphQL;


class CursorPaginatedResponse
{
    /**
     * @var CursorPaginatorInfo
     */
    public $pageInfo;

    /**
     * @var array
     */
    public $edges;

    public function __construct()
    {
        $this->pageInfo = new CursorPaginatorInfo();
        $this->edges = [];
    }

    public function addEdge($cursor, $node)
    {
        $this->edges[] = [
            'node' => $node,
            'cursor' => $cursor,
        ];
    }

}
