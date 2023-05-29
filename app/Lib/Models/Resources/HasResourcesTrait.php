<?php

namespace App\Lib\Models\Resources;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Lib\Models\HasUUIDFieldTrait;
use PhpMyAdmin\Config;

trait HasResourcesTrait
{
    use HasUUIDFieldTrait;

    public static function bootHasResourcesTrait()
    {
        static::deleting(function ($model) {
            foreach ($model->resources as $item) {
                $item->delete();
            }
        });
    }

    public function getResourcesCollectionForUploader()
    {
        return json_encode($this->resources->map->only(['id', 'uri', 'thumbUrl', 'mime_type', 'size', 'url']));
    }

    public function resources($type = Resource::class)
    {
        return $this->morphMany($type, 'owner', 'owner_type', 'owner_id', 'uuid');
    }

    public function resourceOwner()
    {
        return $this->morphOne(static::class, 'owner');
    }

    public function deleteResourcesFolder()
    {
        Storage::disk($this->getResourcesDisk())->deleteDirectory($this->getResourcesPath());
    }

    public function deleteResourcesCacheFolder()
    {
        Storage::disk($this->getResourcesCacheDisk())->deleteDirectory($this->getResourcesPath());
    }

    public function getResourcesPath($type = null, $abs = false)
    {
        $path = $this->getResourcesSection($type) . DIRECTORY_SEPARATOR . $this->getResourcesOwnerId();
        if ($abs) {
            $path = Storage::disk($this->getResourcesDisk())->path($path);
        }
        return $path;
    }

    public function getResourcesCachePath($type = null, $abs = false)
    {
        $path = $this->getResourcesSection($type) . DIRECTORY_SEPARATOR . $this->getResourcesOwnerId();
        if ($abs) {
            $path = Storage::disk($this->getResourcesCacheDisk())->path($path);
        }
        return $path;
    }

    public function getResourcesSection($type = null)
    {
        if (method_exists($this->owner, 'resourcesSection')) {
            return $this->owner->resourcesSection($this);
        }
        if (method_exists($this->owner, 'getFileSection')) {
            return $this->owner->getFileSection();
        }
        return 'resources';
    }

    public function getResourcesOwnerId()
    {
        if (method_exists($this->owner, 'resourcesOwnerId')) {
            return $this->owner->resourcesOwnerId($this);
        }

        if (method_exists($this->owner, 'getFileOwnerId')) {
            return $this->owner->getFileOwnerId();
        }

        return $this->getUUID();
    }

    public function getResourcesDisk()
    {
        if (method_exists($this->owner, 'resourcesDisk')) {
            return $this->owner->resourcesFileDisk($this);
        }

        if (method_exists($this->owner, 'getFileDisk')) {
            return $this->owner->getFileDisk();
        }

        return 'uploads';
    }

    public function getResourcesCacheDisk()
    {
        if (method_exists($this->owner, 'resourcesCacheDisk')) {
            return $this->owner->resourcesCacheDisk($this);
        }

        if (method_exists($this->owner, 'getCacheFileDisk')) {
            return $this->owner->getCacheFileDisk();
        }

        return 'cache';
    }
}
