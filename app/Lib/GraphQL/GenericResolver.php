<?php

namespace App\Lib\GraphQL;

use App\Lib\Filter\CursorPaginatedFilter;
use App\Lib\GraphQL\Exceptions\NotFoundException;
use App\Lib\Filter\Filter;
use App\Lib\Filter\PaginatedFilter;
use App\Lib\Models\Notification;
use App\Lib\Services\IGenericService;
use App\Lib\Services\PermissionService;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\UnauthorizedException;
use Nuwave\Lighthouse\Execution\Utils\Subscription;
use Nuwave\Lighthouse\Schema\Directives\PaginateDirective;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class GenericResolver extends BaseResolver
{

    /**
     * @var Model
     */
    protected $modelClass;
    protected $primaryKeyName;
    protected $moduleName;
    protected $usesPermissions;

    public function __construct($moduleName, $modelClass, $primaryKeyName, $usesPermissions = true)
    {
        $this->modelClass = $modelClass;
        $this->primaryKeyName = $primaryKeyName;
        $this->moduleName = $moduleName;
        $this->usesPermissions = $usesPermissions;
    }


    protected function registerOperations()
    {
        $permissions = [];
        foreach (['create', 'update', 'delete', 'read', 'readAll'] as $op) {
            $permissions[] = $this->constructCompleteOperation($op);
        }
        return PermissionService::instance()->registerOperations($permissions);
    }

    protected function constructCompleteOperation($action)
    {
        return "{$this->moduleName}/{$action}";
    }

    protected function checkPermission($action, $entity = null): Response
    {

        if (!$this->usesPermissions) {
            return true;
        }
        // if ($this->getUser()) {
        //     return $this->getUser()->can($this->constructCompleteOperation($action), $entity);
        // }
        return Gate::forUser($this->getUser())->inspect($this->constructCompleteOperation($action), $entity);
    }

    protected function can($action, $entity = null)
    {

        if (!$this->usesPermissions) {
            return true;
        }
        // if ($this->getUser()) {
        //     return $this->getUser()->can($this->constructCompleteOperation($action), $entity);
        // }
        return $this->checkPermission($action, $entity)->allowed();
    }

    protected function canOrFail($action, $entity = null)
    {
        if (!$this->usesPermissions) {
            return true;
        }
        // if ($this->getUser()) {
        //     if($this->getUser()->can($this->constructCompleteOperation($action), $entity)){
        //         return true;
        //     }
        //     throw new UnauthorizedException();
        // }
        return Gate::forUser($this->getUser())->authorize($this->constructCompleteOperation($action), $entity);
    }

    protected abstract function createServiceInstance();

    protected function getServiceInstance(): IGenericService
    {
        return $this->createServiceInstance();
    }

    protected function constructOutputObject($entity, $action = '')
    {
        $entity->_metadata = $this->addMetadataToOutputObject($entity, $action);
        return $entity;
    }

    /**
     * @deprecated please use the @metadata resolver
     */
    protected function addMetadataToOutputObject($entity, $action = '')
    {
        $ret = [];
        // foreach ($this->getObjectAvailablePermissions($entity, $action) as $op) {
        //     $opName = $this->constructCompleteOperation($op);
        //     if ($this->can($opName, $entity)) {
        //         $ret['permissions'][] = $opName;
        //     }
        // }
        return $ret;
    }

    protected function getObjectAvailablePermissions($entity, $action = '')
    {
        return ['create', 'update', 'delete'];
    }



    protected function constructOutputList($list, $action = '')
    {
        $result = [];

        foreach ($list as $r) {
            $result[] = $this->constructOutputObject($r, $action);
        }

        return collect($result);
    }


    protected function constructPage($paginator, $action = '')
    {
        $pageInfo = null;
        if ($paginator instanceof LengthAwarePaginator) {
            $pageInfo = new CursorPaginatedResponse();
            $pageInfo->pageInfo->setPageInfo($paginator, function ($item) {
                return $this->getItemCursor($item);
            });
            foreach ($paginator->items() as $result) {
                $pageInfo->addEdge($this->getItemCursor($result), $this->constructOutputObject($result));
            }
            return $pageInfo;
        }

        return $paginator;
    }

    protected function getItemCursor(Model $item)
    {
        return base64_encode($item->{$this->getPrimaryKeyName()});
    }

    protected function parseFilter($args)
    {
        return Filter::fromArgs(collect($args)->get('filter', []));
    }

    protected function parsePaginatedFilter($args)
    {
        return PaginatedFilter::fromArgs(collect($args)->get('filter', []));
    }

    protected function parseCursorPaginatedFilter($args)
    {
        return CursorPaginatedFilter::fromArgs(collect($args)->get('filter', []));
    }

    protected function getPrimaryKeyName()
    {
        return $this->primaryKeyName;
    }

    protected function extractIdFromArgs(array $args)
    {
        return isset($args[$this->getPrimaryKeyName()]) ? $args[$this->getPrimaryKeyName()] : null;
    }

    protected function afterCreate($object, $output)
    {
        return $output;
    }

    public function create($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //$args = collect($args);
        if ($this->canOrFail('create')) {
            $object = $this->getServiceInstance()->create($args['input']);
            $ret = $this->constructOutputObject($object, 'create');
            $ret = $this->afterCreate($object, $ret);
            return $ret;
        }
    }

    protected function afterUpdate($object, $output)
    {
        return $output;
    }


    public function update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //$args = collect($args);
        $entity = $this->getServiceInstance()->findByPrimaryKey($this->extractIdFromArgs($args));
        if (!$entity) {
            throw new NotFoundException();
        }
        if ($this->canOrFail('update', $entity)) {
            $object = $this->getServiceInstance()->update($entity, $args['input']);
            $ret = $this->constructOutputObject($object, 'update');
            $ret = $this->afterUpdate($object, $ret);
            return $ret;
        }
    }

    protected function afterDelete($args, $output)
    {
        return $output;
    }

    public function delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //$args = collect($args);
        $entity = $this->getServiceInstance()->findByPrimaryKey($this->extractIdFromArgs($args));
        if (!$entity) {
            throw new NotFoundException();
        }
        if ($this->canOrFail('delete', $entity)) {
            $ret = $this->getServiceInstance()->delete($this->extractIdFromArgs($args));
            $this->afterDelete($args, $ret);
            return $ret;
        }
    }



    public function findByIds($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
    }

    public function findOne($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //$args = collect($args);
        $entity = $this->getServiceInstance()->findOne($args);
        $result = null;
        if (!$entity) {
             return $result;
        }
        if ($this->canOrFail('read', $entity)) {
            $result = $this->constructOutputObject($entity);
        }
        return $result;
    }

    /**
     * Returns items paginated (filter can be applied if comes in args)
     * @return CursorPaginatedResponse | PaginatedResponse
     */
    public function list($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $args = collect($args);
        if ($this->canOrFail('read')) {
            $filter = $this->parsePaginatedFilter($args);

            $list = $filter->paginate($this->getServiceInstance()->listObjects($filter));

            return $this->constructPage($list);
        }
    }

    public function findById($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //$args = collect($args);
        $entity = $this->getServiceInstance()->findByPrimaryKey($this->extractIdFromArgs($args));
        $result = null;
        if (!$entity) {
            return null;
        }
        if ($this->canOrFail('read', $entity)) {
            $result = $this->constructOutputObject($entity);
        }
        return $result;
    }

    /**
     * Returns all items (filter can be applied if comes in args)
     */
    public function listAll($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        if ($this->canOrFail('readAll')) {
            //$args = collect($args);
            $list = $this->getServiceInstance()->listObjects($this->parseFilter($args));
            return $this->constructOutputList($list->get());;
        }
    }
}
