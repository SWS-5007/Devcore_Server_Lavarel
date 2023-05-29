<?php

namespace App\Lib\GraphQL;


class CursorPaginatorInfo
{
    public $first;
    public $last;
    public $totalCount;
    public $startCursor;
    public $endCursor;
    public $currentCount;
    public $hasNextPage;
    public $hasPrevPage;
    public $currentPage;

    public function setPageInfo(\Illuminate\Pagination\LengthAwarePaginator $page, callable $itemCursorFn)
    {
        $this->first = $page->firstItem();
        $this->last = $page->lastItem();
        $this->totalCount = $page->total();
        $this->hasNextPage = $page->hasMorePages();
        $this->hasPrevPage = $page->currentPage() > 1 && $page->hasPages();
        $this->currentCount = count($page->items());
        $this->startCursor = !$page->isEmpty() ? $itemCursorFn($page->getCollection()->first()) : null;
        $this->endCursor = !$page->isEmpty() ? $itemCursorFn($page->getCollection()->last()) : null;
    }
}
