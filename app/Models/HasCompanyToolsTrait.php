<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Log;

trait HasCompanyToolsTrait
{

    public function companyToolsRelation()
    {
        return $this->morphMany(ModelHasCompanyTool::class, 'model', 'model_type', 'model_id', 'id');
    }

    public function companyTools()
    {
        return $this->hasManyThrough(CompanyTool::class, ModelHasCompanyTool::class, 'model_id', 'id', 'id', 'company_tool_id')->where('model_type', array_search(static::class, Relation::morphMap()) ?: static::class);
    }

    public function attachCompanyTool($companyTool, $reload = true)
    {
        if (!is_array($companyTool)) {
            $companyTool = [$companyTool];
        }
        $ids = [];
        foreach ($companyTool as $cr) {
            if ($cr instanceof Model) {
                $ids[] = $cr->getKey();
            } else {
                $ids[] = $cr;
            }
        }
        $validTools = CompanyTool::whereIn('id', $ids)->get();
        //$validCompanyRoles = CompanyRole::whereIn('id', $ids)->get();
        $created = [];
        foreach ($validTools as $cr) {
            $relation = $this->hasCompanyTool($cr);
            if (!$relation) {

                $relation = new ModelHasCompanyTool();
                $relation->company_tool_id = $cr->getKey();
                $relation->model_type = array_search(static::class, Relation::morphMap()) ?: static::class;
                $relation->model_id = $this->getKey();
                $relation->save();
                $created[] = $relation;
               // echo $relation->model_type;
            }
        }
        if (count($created) && $reload) {
            $this->load(['companyTools', 'companyToolsRelation']);
        }

        return $created;
    }

    public function detachCompanyTool($tool, $reload = true)
    {
        if (!is_array($tool)) {
            $tool = [$tool];
        }
        $ids = [];
        foreach ($tool as $cr) {
            if ($cr instanceof Model) {
                $ids[] = $cr->getKey();
            } else {
                $ids[] = $cr;
            }
        }
        $ret = 0;
        if (count($ids)) {
            $ret = $this->companyToolsRelation()->whereIn('company_tool_id', $ids)->delete();
            if ($reload) {
                $this->load(['companyTools', 'companyToolsRelation']);
            }
        }
        return $ret;
    }

    public function syncCompanyTools(array $companyTools)
    {

        $toDelete = [];
        $toAdd = [];

        $ids = [];
        foreach ($companyTools as $cr) {
            if ($cr instanceof Model) {
                $ids[] = $cr->getKey();
            } else {
                $ids[] = $cr;
            }
        }

        foreach ($ids as $toAddId) {
            if (!$this->hasCompanyTool($toAddId)) {
                $toAdd[] = $toAddId;
            }
        }

        foreach ($this->companyTools as $old) {
            if (!in_array($old->getKey(), $ids)) {
                $toDelete[] = $old->getKey();
            }
        }

        $shouldRefresh = false;

        if (count($toDelete)) {
            $shouldRefresh = true;
            $this->detachCompanyTool($toDelete, false);
        }

        if (count($toAdd)) {
            $shouldRefresh = true;
            $this->attachCompanyTool($toAdd, false);
        }

        if ($shouldRefresh) {
            $this->load(['companyTools', 'companyToolsRelation']);
        }
    }

    public function hasCompanyTool($tool)
    {
        $id = $tool;
        if ($tool instanceof Model) {
            $id = $tool->getKey();
        }
        return $this->companyTools->first(function ($value, $key) use ($id) {
            return $value->id === $id;
        });
    }
}
