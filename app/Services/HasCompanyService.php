<?php

namespace App\Services;

use App\Lib\GraphQL\Exceptions\UnauthenticatedException;
use App\Lib\Services\GenericService;

class HasCompanyService extends GenericService
{

    protected function getUserCompanyId()
    {
        if (!$this->getUser()) {
            throw new UnauthenticatedException();
        }

        return $this->getUser() ? $this->getUser()->company_id : null;
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Model::class $modelClass
     */
    protected function __construct($modelClass, $hasSoftDeletes = false)
    {
        parent::__construct($modelClass, $hasSoftDeletes);
    }

    protected function fillFromArray($option, $data, $instance)
    {
        $instance = parent::fillFromArray($option, $data, $instance);
        if ($this->getUserCompanyId()) {
            $instance->company_id = $this->getUser()->company_id;
        }
        return $instance;
    }

    public function getBaseQuery($with = [])
    {
        $query = parent::getBaseQuery($with);
        // if ($this->getUserCompanyId()) {
        //     $query->where('company_id', $this->getUser()->company_id);
        // }
        return $query;
    }
}
