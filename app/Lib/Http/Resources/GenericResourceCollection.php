<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lib\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Description of GenericResourceCollection
 *
 * @author pablo
 */
class GenericResourceCollection extends ResourceCollection
{

    protected $requestUser;
    protected $pagination;
    protected $links;
    protected $paginated = false;

    public function __construct($resource, $requestUser)
    {
        if ($resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $this->paginated = true;
            $this->pagination = [
                'current_page' => (int)$resource->currentPage(),
                'from' => (int)$resource->firstItem(),
                'last_page' => (int)$resource->lastPage(),
                'per_page' => (int)$resource->perPage(),
                'to' => (int)$resource->lastItem(),
                'total' => (int)$resource->total(),
                'count' => (int)$resource->count(),
            ];

            $this->links = [
                'first' => $resource->url(1),
                'last' => $resource->url($resource->lastPage()),
                'prev' => $resource->previousPageUrl(),
                'next' => $resource->nextPageUrl(),
            ];

            $resource = $resource->getCollection();
        }
        $this->requestUser = $requestUser;
        parent::__construct($resource);
    }

    public function transformItems($request, $collection, $user)
    { }

    public function toArray($request)
    {
        $this->transformItems($request, $this->collection, $this->requestUser);
        if (!$this->paginated) {
            return $this->collection;
        }
        return [
            'results' => $this->collection,
            $this->mergeWhen(property_exists($this, 'pagination') && $this->pagination, function () {
                return ['pagination' => $this->pagination];
            }),
            $this->mergeWhen(property_exists($this, 'links') && $this->links, function () {
                return ['links' => $this->links];
            }),
        ];
    }
}
