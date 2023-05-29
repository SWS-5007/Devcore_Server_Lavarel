<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lib\Models;

use App\Lib\Storage\Image;
use Illuminate\Support\Str;

/**
 * Description of ImageOwnerModel
 *
 * @author pramirez
 */
trait HasImageTrait
{

    use HasFileTrait;


    public function setImage($file)
    {
        return $this->setFile($file);
    }

    public function getFile()
    {
        if (!$this->file) {
            $this->file = new Image();
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

    protected function getDefaultImageName()
    {
        return property_exists($this, 'default_image_name') ? $this->default_image_name : null;
    }

    protected function getFileFieldValue()
    {
        $withDefault = true;
        if (func_num_args() > 0) {
            $withDefault = func_get_arg(0);
        }
        return $this->{$this->getImageColumn()} ? $this->{$this->getImageColumn()} : (($withDefault) ? $this->getDefaultImageName() : null);
    }


    public function getImageColumn()
    {
        return property_exists($this, 'imageColumn') ? $this->imageColumn : parent::getFileColumn();
    }

    public function getFileColumn(){
        return $this->getImageColumn();
    }

    public function hasImage()
    {
        return $this->hasFile();
    }

    public function saveImage($file, $filename = null, $autoDelete = true)
    {
        return $this->saveFile($file, $filename, $autoDelete);
    }

    public function deleteImage()
    {
        return $this->deleteFile();
    }

    public function saveImageFromStream($contents, $filename = null, $autoDelete = true)
    {
        return $this->saveFileFromStream($contents, $filename, $autoDelete);
    }

    public function getImageUrl($size = '0x0', $ignoreCache = false)
    {
        return $this->getFile()->getImageUrl($size, $ignoreCache);
    }

    public function getFileUrlAttribute()
    {
        return $this->getImageUrl();
    }
}
