<?php

namespace App\Lib\Models\Resources;

use App\Lib\Models\CachableModel;
use App\Lib\Utils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ResourceCollection extends CachableModel
{
    use HasResourcesTrait {
    getResourcesOwnerId as traitGetResourcesOwnerId;
    getResourcesSection as traitGetResourcesSection;
    getResourcesCacheDisk as traitGetResourcesCacheDisk;
    getResourcesDisk as traitGetResourcesDisk;
    }
    //protected $table = "resource_collection";
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->deleteResourcesFolder();
            $model->deleteResourcesCacheFolder();
        });
    }



    public function deleteResourcesFolder()
    {
        Storage::disk($this->getResourcesDisk())->deleteDirectory($this->getResourcesPath());
    }

    public function deleteResourcesCacheFolder()
    { }

    public function owner()
    {
        return $this->morphTo('resourceCollectionOwner', 'owner_type', 'owner_id', 'uuid');
    }


    public function getUrl()
    {
        return route(config('resources.controller.name') . '.show', [
            'parameters' =>  Utils::normalizeFileNameUrl("{$this->getResourcesSection()}/{$this->getResourcesOwnerId()}"),
            'id' => $this->getUUID()
        ]);
    }



    public function getOrLoadOwner()
    {
        if (!$this->relationLoaded('owner')) {
            $this->owner()->get();
        }
        return $this->owner;
    }


    public function getResourcesSection($type = null)
    {
        if (method_exists($this->owner, 'resourcesCollectionSection')) {
            return $this->owner->resourcesCollectionSection($this);
        }
        return $this->traitGetResourcesSection($type);
    }


    public function getResourcesOwnerId()
    {
        if (method_exists($this->owner, 'resourcesCollectionOwnerId')) {
            return $this->owner->resourcesCollectionOwnerId($this);
        }
        return $this->traitGetResourcesOwnerId();
    }

    public function getResourcesDisk()
    {
        if (method_exists($this->owner, 'resourcesCollectionDisk')) {
            return $this->owner->resourcesCollectionDisk($this);
        }
        return $this->traitGetResourcesDisk();
    }

    public function getResourcesCacheDisk()
    {
        if (method_exists($this->owner, 'resourcesCollectionCacheDisk')) {
            return $this->owner->resourcesCollectionCacheDisk($this);
        }
        return $this->traitGetResourcesCacheDisk();
    }
}
