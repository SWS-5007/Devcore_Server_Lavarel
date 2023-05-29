<?php

namespace App\Lib\Services;

use App\Lib\Context;
use \Illuminate\Support\Facades\DB;
use App\Lib\Validators\GenericValidator;
use App\Lib\Utils;
use App\Lib\Filters\GenericFilter;
use App\Lib\Filters\SortParam;
use Illuminate\Support\Facades\Lang;

/**
 *
 * @author pramirez
 */
abstract class GenericService implements IGenericService
{

    /**
     *
     * @var \Illuminate\Database\Eloquent\Model::class
     */
    protected $modelClass;
    protected $user;
    protected $hasSoftDeletes;
    protected $modelConfig;


    /**
     *
     * @param \Illuminate\Database\Eloquent\Model::class $modelClass
     */
    protected function __construct($modelClass, $hasSoftDeletes = false)
    {
        $this->modelClass = $modelClass;
        $this->hasSoftDeletes = $hasSoftDeletes;
    }

    public function getPrimaryKeyName()
    {
        if (property_exists($this, 'primaryKeyName')) {
            return $this->primaryKeyName;
        }

        return app($this->modelClass)->getKeyName();
    }

    public function getBaseQuery($with = [])
    {
        return $this->modelClass::with($with);
    }

    protected function validate($data, $object, $option)
    {

        if ($data == null) {
            throw new \Exception(trans('messages.element_null'));
        }

        return $this->getValidator($data, $object, $option)->execute();
    }

    /**
     *
     * @param type $data
     * @param type $object
     * @param type $option
     * @return GenericValidator
     */
    protected function getValidator($data, $object, $option)
    {
        return (new GenericValidator([], $data, $object, $option, Lang::get('validation') ));
    }

    protected function prepareData($option, &$data, &$object)
    {
        $data = Utils::removeEmtpyValues($data);
    }

    protected function getModelConfig()
    {
        if (!$this->modelConfig) {
            $this->modelConfig = with(new $this->modelClass);
        }
        return $this->modelConfig;
    }

    public function getDB()
    {
    }


    /**
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user ? $this->user : Context::get()->getUser();
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     *
     * @param type $data
     * @param type $object
     * @return object (the modified object)
     */
    protected function beforeCreate($data, $object)
    {
        return $object;
    }

    /**
     *
     * @param type $data
     * @param type $object
     * @return object (the modified object)
     */
    protected function creating($data, $object)
    {
        return $object;
    }

    /**
     *
     * @param type $data
     * @param type $object
     * @return object (the modified object)
     */
    protected function created($data, $object)
    {
        return $object;
    }

    protected function afterCreate($data, $object)
    {
        return $object;
    }

    protected function fillFromArray($option, $data, $instance)
    {
        //$instance->fill($data);
        return $instance;
    }

    /**
     *
     * @param type $object
     * @return type
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create($data)
    {
        $instance = new $this->modelClass;
        //preparo los datos para la creación
        $this->prepareData('create', $data, $instance);
        $validator = $this->getValidator($data, $instance, 'create');
        if (!$validator->fails()) {
            DB::transaction(function () use ($data, $instance) {
                $instance = $this->beforeCreate($data, $instance);
                $instance = $this->fillFromArray('create', $data, $instance);
                $instance = $this->creating($data, $instance);
                $instance->save();
                $instance = $this->created($data, $instance);
            });
            $instance = $this->afterCreate($data, $instance);
            return $instance;
        }

        throw new \Illuminate\Validation\ValidationException($validator);
    }

    /**
     *
     * @param type $data
     * @param type $object
     * @return object (the modified object)
     */
    protected function beforeUpdate($data, $object)
    {
        return $object;
    }

    /**
     *
     * @param type $data
     * @param type $object
     * @return object (the modified object)
     */
    protected function updating($data, $object)
    {
        return $object;
    }

    /**
     *
     * @param type $data
     * @param type $object
     * @return object (the modified object)
     */
    protected function updated($data, $object)
    {
        return $object;
    }

    protected function afterUpdate($data, $object)
    {
        return $object;
    }

    public function update($object, $data)
    {
        if ($object != null) {
            //preparo los datos para la creación
            $this->prepareData('update', $data, $object);
            $validator = $this->getValidator($data, $object, 'update');
            if (!$validator->fails()) {
                DB::transaction(function () use ($data, $object) {
                    $object = $this->beforeUpdate($data, $object);
                    $object = $this->fillFromArray('update', $data, $object);
                    $object = $this->updating($data, $object);
                    $object->save();
                    $object = $this->updated($data, $object);
                });
                $object = $this->afterUpdate($data, $object);
                return $object;
            }
            throw new \Illuminate\Validation\ValidationException($validator);
        } else {
            throw new \Exception(trans('messages.element_null'));
        }
    }

    protected function beforeDelete($object)
    {
        return $object;
    }

    protected function deleting($object)
    {
    }

    protected function getForDelete($pk)
    {
        return $this->findByPrimaryKey($pk, $this->hasSoftDeletes);
    }

    public function delete($pk, $permanent = false)
    {
        $instance = $this->getForDelete($pk);

        if ($instance != null) {
            $result = null;
            DB::transaction(function () use ($permanent, $instance, $result) {
                $this->beforeDelete($instance);
                if ($permanent && $this->hasSoftDeletes) {
                    $result = $instance->forceDelete();
                } else {
                    $result = $instance->delete();
                }

                $this->deleting($instance);
            });
           $this->deleted($instance);
            return $result;
        }
    }

    public function restore($pk)
    {
        $instance = $this->findByPrimaryKey($pk, $this->hasSoftDeletes);
        if ($instance != null) {
            $instance->restore();
        }
        return $instance;
    }

    /**
     *
     * @param type $pk
     * @param type $includeTrashed
     * @return
     */
    public function findByPrimaryKey($pk, $includeTrashed = false, $with = [])
    {
        $class = $this->getBaseQuery($with);
        if ($includeTrashed && $this->hasSoftDeletes) {
            return $class->withTrashed()->find([$this->getPrimaryKeyName() => $pk])->first();
        } else {
            return $class->find([$this->getPrimaryKeyName() => $pk])->first();
        }
    }

    public function find($includeTrashed = false, $with = [])
    {
        $class = $this->getBaseQuery($with);
        if ($includeTrashed && $this->hasSoftDeletes) {
            return $class->withTrashed();
        } else {
            return $class;
        }
    }

    protected function validateFilterValue($filterValue)
    {
        $key = $filterValue['k'];
        $field = $filterValue['f'];
        $comparator = $filterValue['c'];
        $value = $filterValue['v'];
        if ($field == '' || $value == '' || $comparator == '') {
            return false;
        }
        return true;
    }

    protected function getOrderPairs($orderStr)
    {
        if ($orderStr != '') {
            $ret = [];
            $orderPars = explode(',', $orderStr);
            foreach ($orderPars as $closure) {
                $direction = "ASC";
                $field = $closure;
                if (substr($closure, 0, 1) == '-') {
                    $direction = "DESC";
                    $field = substr($field, 1);
                }
                $ret[$field] = $direction;
            }
            return $ret;
        }
        return [];
    }

    protected function sort($query, $order = 'id')
    {
        // $orderPars = $this->getOrderPairs($order);
        // foreach ($orderPars as $field => $direction) {
        //     $query = ((new SortParam($field, $direction))->apply($query));
        // }

        return $query;
    }

    protected function parseEnabledFilter($query, &$filter)
    {
        if (isset($filter['show'])) {
            switch ($filter['show']) {
                case 'all':
                    $query = $query->withTrashed();
                    break;
                case 'trashed':
                    $query = $query->onlyTrashed();
                    break;
            }
        }

        unset($filter['show']);
        return $query;
    }

    function listObjects($filter = null, $order = null)
    {
        if ($order) {
            $filter->parseSortStr($order);
        }
        if ($filter) {
            return $filter->apply($this->getBaseQuery());
        }

        return $this->getBaseQuery();
    }

    /**
     *
     * @param type $id
     */
    function findOne($filter, $includeTrashed = false, $with = [])
    {

        if ($filter) {
            return $filter->apply($this->getBaseQuery())->first();
        }
        return $this->getBaseQuery()->first();
    }
}
