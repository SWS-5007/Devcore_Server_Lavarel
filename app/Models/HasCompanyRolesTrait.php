<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Log;

trait HasCompanyRolesTrait
{

    public function companyRolesRelation()
    {
        return $this->morphMany(ModelHasCompanyRole::class, 'model', 'model_type', 'model_id', 'id');
    }

    public function companyRoles()
    {
        return $this->hasManyThrough(CompanyRole::class, ModelHasCompanyRole::class, 'model_id', 'id', 'id', 'company_role_id')->where('model_type', array_search(static::class, Relation::morphMap()) ?: static::class);
    }

    public function attachCompanyRole($companyRole, $reload = true)
    {
        if (!is_array($companyRole)) {
            $companyRole = [$companyRole];
        }
        $ids = [];
        foreach ($companyRole as $cr) {
            if ($cr instanceof Model) {
                $ids[] = $cr->getKey();
            } else {
                $ids[] = $cr;
            }
        }

        $validCompanyRoles = CompanyRole::whereIn('id', $ids)->get();
        $created = [];
        foreach ($validCompanyRoles as $cr) {
            $relation = $this->hasCompanyRole($cr);
            if (!$relation) {
                $relation = new ModelHasCompanyRole();
                $relation->company_role_id = $cr->getKey();
                $relation->model_type = array_search(static::class, Relation::morphMap()) ?: static::class;
                $relation->model_id = $this->getKey();
                $relation->save();
                $created[] = $relation;
            }
        }
        if (count($created) && $reload) {
            $this->load(['companyRoles', 'companyRolesRelation']);
        }

        return $created;
    }

    public function detachCompanyRole($companyRole, $reload = true)
    {
        if (!is_array($companyRole)) {
            $companyRole = [$companyRole];
        }
        $ids = [];
        foreach ($companyRole as $cr) {
            if ($cr instanceof Model) {
                $ids[] = $cr->getKey();
            } else {
                $ids[] = $cr;
            }
        }
        $ret = 0;
        if (count($ids)) {
            $ret = $this->companyRolesRelation()->whereIn('company_role_id', $ids)->delete();
            if ($reload) {
                $this->load(['companyRoles', 'companyRolesRelation']);
            }
        }
        return $ret;
    }

    public function syncCompanyRoles(array $companyRoles)
    {

        $toDelete = [];
        $toAdd = [];

        $ids = [];
        foreach ($companyRoles as $cr) {
            if ($cr instanceof Model) {
                $ids[] = $cr->getKey();
            } else {
                $ids[] = $cr;
            }
        }

        foreach ($ids as $toAddId) {
            if (!$this->hasCompanyRole($toAddId)) {
                $toAdd[] = $toAddId;
            }
        }

        foreach ($this->companyRoles as $old) {
            if (!in_array($old->getKey(), $ids)) {
                $toDelete[] = $old->getKey();
            }
        }

        $shouldRefresh = false;

        if (count($toDelete)) {
            $shouldRefresh = true;
            $this->detachCompanyRole($toDelete, false);
        }

        if (count($toAdd)) {
            $shouldRefresh = true;
            $this->attachCompanyRole($toAdd, false);
        }

        if ($shouldRefresh) {
            $this->load(['companyRoles', 'companyRolesRelation']);
        }
    }

    public function hasCompanyRole($companyRole)
    {
        $id = $companyRole;
        if ($companyRole instanceof Model) {
            $id = $companyRole->getKey();
        }
        return $this->companyRoles->first(function ($value, $key) use ($id) {
            return $value->id === $id;
        });
    }
}
