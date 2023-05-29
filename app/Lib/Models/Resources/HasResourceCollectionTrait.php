<?php

namespace App\Lib\Models\Resources;

trait HasResourceCollectionTrait
{

    public static function bootHasResourceCollectionTrait()
    {

        static::deleting(function ($model) {
            foreach ($model->resourceCollections as $item) {
                $item->delete();
            }
            $model->deleteResourceCollectionDir();
            $model->deleteResourcesCollectionCacheDir();
        });
    }

    public function deleteResourceCollectionDir()
    { }

    public function deleteResourcesCollectionCacheDir()
    { }

    public function resourceCollectionOwner()
    {
        return $this->morpOne(static::class, 'owner');
    }

    public function getResourcesOwnerKey()
    {
        return (property_exists($this, 'resourcesOwnerKeyName')) ? $this->resourcesOwnerKeyName : 'uuid';
    }

    public function resourceCollections()
    {
        return $this->morphMany(ResourceCollection::class, 'owner', 'owner_type', 'owner_id', $this->getResourcesOwnerKey());
    }
}
