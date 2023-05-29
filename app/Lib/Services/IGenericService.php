<?php

namespace App\Lib\Services;

use App\Lib\Filters\GenericFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 * @author pramirez
 */
interface IGenericService
{

    /**
     * 
     * @param type $object
     */
    function create($data);

    /**
     * 
     * @param type $object
     */
    function update($object, $data);

    /**
     * 
     * @param mixed $id
     */
    function delete($id, $permanent = false);

    /**
     * 
     * @param type $id
     */
    function restore($id);

    /**
     * 
     * @param type $id
     */
    function findByPrimaryKey($id, $includeTrashed = false, $with = []);

      /**
     * 
     * @param type $id
     */
    function findOne($filter, $includeTrashed = false, $with = []);

    /**
     * 
     * @param type $filter
     * @return Builder
     */
    function listObjects($filter=null, $order = 'id');
    function setUser($user);
}
