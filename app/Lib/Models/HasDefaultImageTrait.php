<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lib\Models;

use App\Lib\Storage\Image;
use Illuminate\Support\Str;

trait HasDefaultImageTrait
{
    use HasImageTrait;
    protected $defaultFile = null;


    protected static function bootHasDefaultImageTrait()
    {

        static::updated(function ($model) {
            if ($model->shouldRegenerateDefaultImage()) {
                $model->deleteDefaultImage();
            }
        });

        //delete image on delete entity
        static::deleted(function ($model) {
            $model->deleteDefaultImage();
        });
    }

    protected function getDefaultImageText()
    {
        return "";
    }

    protected function defaultImageName()
    {
        return (property_exists($this, 'default_image_name') ? $this->default_image_name : md5($this->getFileOwnerId()) . '.png');
    }

    protected function shouldRegenerateDefaultImage()
    {
        return false;
    }

    protected function getGeneratedDefaultImageName()
    {
        return  $this->defaultImageName() . '?generate=text&text=' . $this->getDefaultImageText();;
    }

    protected function getFileFieldValue()
    {
        $withDefault = true;
        if (func_num_args() > 0) {
            $withDefault = func_get_arg(0);
        }
        return $this->{$this->getImageColumn()} ? $this->{$this->getImageColumn()} : (($withDefault) ? $this->getGeneratedDefaultImageName() : null);
    }

    public function setDefaultImage($file)
    {
        return $this->setFile($file);
    }

    public function getDefaultImage()
    {
        if (!$this->defaultFile) {
            $this->defaultFile = new Image();
            $this->defaultFile->baseFileConfigKey = $this->getBaseFileConfigKey();
            $this->defaultFile->fileConfigKey = $this->getFileConfigKey();
            $this->defaultFile->fileDisk = $this->getFileDisk();
            $this->defaultFile->cacheFileDisk = $this->getCacheFileDisk();
            $this->defaultFile->fileOwnerId = $this->getFileOwnerId();
            $this->defaultFile->fileSection = $this->getFileSection();
            $this->defaultFile->setFileName($this->defaultImageName());
        }
        return $this->defaultFile;
    }

    public function deleteDefaultImage()
    {
        return $this->getDefaultImage()->deleteFile();
    }
    public function deleteImage()
    {
        $ret = $this->deleteFile();
        $this->deleteDefaultImage();
        return $ret;
    }
}
