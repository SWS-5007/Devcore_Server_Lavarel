<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lib\Models;

use App\Lib\Storage\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Description of ImageOwnerModel
 *
 * @author pramirez
 */
trait HasFileTrait
{

    protected $file_uuid;
    protected $file = null;

    public static function bootHasFileTrait()
    {
        //delete image on delete entity
        static::deleted(function ($model) {
            $model->getFile()->deleteFile();
        });

        static::retrieved(function ($model) {
            if (!config('custom.importing', false)) {
                //$model->getFile();
            }
        });
    }

    function getTempId()
    {
        if (method_exists($this, 'getUUID')) {
            return $this->getUUID();
        }

        if ($this->getKey()) {
            return $this->getKey();
        }
        if (!$this->file_uuid) {
            $this->file_uuid = Str::uuid();
        }
        return $this->file_uuid;
    }

    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function getFile()
    {
        if (!$this->file) {
            $this->file = new File();
            $this->file->baseFileConfigKey = $this->getBaseFileConfigKey();
            $this->file->fileConfigKey = $this->getFileConfigKey();
            $this->file->fileDisk = $this->getFileDisk();
            $this->file->cacheFileDisk = $this->getCacheFileDisk();
            $this->file->fileOwnerId = $this->getFileOwnerId();
            $this->file->fileSection = $this->getFileSection();
            $this->file->setFileName($this->getFileFieldValue());
        }
        return $this->file;
    }

    public function getFileUrlAttribute()
    {
        return $this->getFile()->getFileUrl();
    }

    protected function getFileColumn()
    {
        return property_exists($this, 'fileColumn') ? $this->fileColumn : 'uri';
    }


    public function getFileFieldValue()
    {
        return $this->{$this->getFileColumn()} ? $this->{$this->getFileColumn()} : null;
    }

    public function setFileFieldValue($value)
    {
        $this->{$this->getFileColumn()} = $value;
        return $this;
    }


    protected function getBaseFileConfigKey()
    {
        if (property_exists($this, 'baseFileConfigKey')) {
            return $this->baseFileConfigKey;
        }
        return 'files';
    }


    function getFileConfigKey()
    {
        if (property_exists($this, 'fileConfigKey')) {
            return  $this->fileConfigKey;
        }
        return 'default';
    }

    public function getFileSection()
    {
        if (property_exists($this, 'fileSection')) {
            return $this->fileSection;
        }
        return 'files';
    }

    public function getFileDisk()
    {
        if (property_exists($this, 'fileDisk')) {
            return $this->fileDisk;
        }
        return 'uploads';
    }

    public function getCacheFileDisk()
    {
        if (property_exists($this, 'cacheFileDisk')) {
            return $this->cacheFileDisk;
        }
        return 'cache';
    }

    public function getFileOwnerId()
    {
        return $this->getTempId();
    }

    public function processFileUpload($data){
        $this->getFile()->processFileUpload($data);
        $this->setFileFieldValue($this->getFile()->getFileName());
    }

    public function saveFile($file, $filename = null, $autoDelete = true)
    {
        $this->getFile()->saveFile($file, $filename, $autoDelete);
        $this->setFileFieldValue($this->getFile()->getFileName());
    }

    public function saveFileFromStream($contents, $filename = null, $autoDelete = true)
    {
        $this->getFile()->saveFileFromStream($contents, $filename, $autoDelete);
        $this->setFileFieldValue($this->getFile()->getFileName());
    }

    public function deleteFile()
    {
        $this->getFile()->deleteFile();
        $this->setFileFieldValue(null);
    }


    public function hasFile()
    {
        return $this->getFileFieldValue(false);
    }

    public function getFileName()
    {
        return $this->getFile()->getFileName();
    }

    public function getMimeExtension()
    {
        return $this->getFile()->getMimeExtension();
    }

    public function getFileDir()
    {
        return $this->getFile()->getFileDir();
    }

    public function fileExists()
    {
        return $this->getFile()->fileExists();
    }

}
