<?php

namespace App\Lib\Models\Resources;

use App\Lib\Models\CachableModel;
use App\Lib\Models\HasFileTrait;
use App\Lib\Models\HasPropertiesColumnTrait;
use App\Lib\Models\HasUUIDFieldTrait;
use App\Lib\Storage\File;
use App\Lib\Storage\Image;
use App\Lib\Utils;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class Resource extends CachableModel
{
    use HasUUIDFieldTrait, HasFileTrait, HasPropertiesColumnTrait;

    //protected $table = "resources";
    protected $guarded = [];

    protected $appends = [
        'thumbUrl',
        'url',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->deleteFile();
        });
    }

    public function owner()
    {
        return $this->morphTo('resourceOwner', 'owner_type', 'owner_id', 'uuid');
    }

    public function getPath()
    {
        return $this->owner->getResourcesPath();
    }

    public function getUrlAttribute()
    {
        return $this->generatePublicUrl();
    }

    public function generatePublicUrl()
    {
        return route(config('resources.controller.name') . '.show', [
            'parameters' => Utils::normalizeFileNameUrl("{$this->owner->getResourcesSection()}/{$this->owner->getResourcesOwnerId()}"),
            'id' => $this->getUUID(),
        ]);
    }

    public function generatePrivateUrl($userId)
    {
        return route(config('resources.controller.name') . '.show-private', Crypt::encrypt([
            'folder' => $this->owner->getResourcesOwnerId(),
            'id' => $this->getKey(),
            'user_id' => $userId
        ]));
    }

    public function generateDeleteUrl()
    {
        return route(config('resources.controller.name') . '.delete', [
            'section' => 'resources',
            'folder' => 'resource',
            'id' => $this->getUUID(),
        ]);
    }


    function getResourceConfig($key)
    {
        return Config::get('resources.' . $this->resourcesConfigKey() . '.' . $key, Config::get('resources.default.' . $key));
    }

    public function resourcesConfigKey()
    {
        if (property_exists($this, 'resourcesConfigKey')) {
            return $this->resourcesConfigKey;
        }
        return 'default';
    }

    public function getFileConfigKey()
    {
        return Utils::normalizeFileNameUrl(Utils::parseTemplateString($this->getResourceConfig('file-config'), [
            'section' => $this->getFileSection(),
        ]));
    }


    public function getFile()
    {
        if (!$this->file) {
            if ($this->display_type === 'image') {
                $this->file = new Image();
            } else {
                $this->file = new File();
            }
            $this->file->title = $this->title;
            $this->file->baseFileConfigKey = $this->getBaseFileConfigKey();
            $this->file->fileConfigKey = $this->getFileConfigKey();
            $this->file->fileOwnerId = $this->getFileOwnerId();
            $this->file->fileSection = $this->getFileSection();
            $this->file->setFileName($this->getFileFieldValue());
            $this->file->setFilesize($this->size);
            $this->file->setMimeType($this->mime_type);
        }
        return $this->file;
    }


    public function saveFile($file)
    {
        $this->getFile()->saveFile($file);
        $this->{$this->getFileColumn()} = $this->file->getFileName();
        $this->mime_type = $this->file->getFileMimeType();
        $this->size = $this->file->getFileSize();
    }

    public function deleteFile()
    {
        return $this->getFile()->deleteFile();
    }

    public function getThumbUrlAttribute()
    {
        if ($this->display_type === 'image') {
            return $this->getFile()->getImageUrl('120x120');
        } else {
            return asset('images/mimes/128px/' . $this->getFile()->getMimeExtension() . '.png');
        }
    }


    public function getOrLoadOwner()
    {
        if (!$this->relationLoaded('owner')) {
            $this->owner()->get();
        }
        return $this->owner;
    }


    //file config section
    public function getFileOwnerId()
    {
        if ($this->getOrLoadOwner()) {
            if (method_exists($this->owner, 'getResourcesOwnerId')) {
                return $this->owner->getResourcesOwnerId($this);
            }
            if (method_exists($this->owner, 'getFileOwnerId')) {
                return $this->owner->getFileOwnerId();
            }
        }
        return $this->getUUID();
    }


    public function getFileSection()
    {
        if ($this->getOrLoadOwner()) {
            if (method_exists($this->owner, 'getResourcesSection')) {
                return $this->owner->getResourcesSection($this);
            }
            if (method_exists($this->owner, 'getFileSection')) {
                return $this->owner->getFileSection();
            }
        }
        return 'resources';
    }

    public function getFileDisk()
    {
        if ($this->getOrLoadOwner()) {
            if (method_exists($this->owner, 'getResourcesFileDisk')) {
                return $this->owner->getResourcesFileDisk($this);
            }
            if (method_exists($this->owner, 'getFileDisk')) {
                return $this->owner->getFileDisk();
            }
        }
        return 'uploads';
    }

    public function getCacheFileDisk()
    {
        if ($this->getOrLoadOwner()) {
            if (method_exists($this->owner, 'getResourcesCacheFileDisk')) {
                return $this->owner->getResourcesCacheFileDisk($this);
            }
            if (method_exists($this->owner, 'getCacheFileDisk')) {
                return $this->owner->getFileDisk();
            }
        }
        return 'cache';
    }
}
