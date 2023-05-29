<?php

namespace App\Lib\Storage;

use App\Lib\Models\Resources\Resource;
use App\Lib\Utils;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileTrait
{

    protected $_fileName;
    protected $_fileMimeType;
    protected $_fileSize;

    function baseFileConfigKey()
    {
        if (property_exists($this, 'baseFileConfigKey')) {
            return  $this->baseFileConfigKey;
        }
        return 'files';
    }

    function setBaseFileConfigKey($key)
    {
        $this->baseFileConfigKey = $key;
        return $this;
    }

    function setFileConfigKey($key)
    {
        $this->fileConfigKey = $key;
        return $this;
    }

    function getFileConfig($key)
    {
        return Config::get($this->baseFileConfigKey() . '.' . $this->getFileSection() . '.' . $key, Config::get($this->baseFileConfigKey() . '.default.' . $key));
    }


    function getFileTemplateVars()
    {
        return [
            'section' => $this->getFileSection(),
            'folder' => $this->getFileOwnerId(),
        ];
    }


    public function getFileSection()
    {
        if (property_exists($this, 'fileSection')) {
            return $this->fileSection;
        }
        return 'files';
    }

    public function setFileSection($section)
    {
        $this->fileSection = $section;
        return $this;
    }

    public function getFileDisk()
    {
        if (property_exists($this, 'fileDisk')) {
            return $this->fileDisk;
        }
        return $this->getFileConfig('disk');
    }

    public function setFileDisk($diskName)
    {
        $this->fileDisk = $diskName;
        return $this;
    }

    public function getCacheFileDisk()
    {
        if (property_exists($this, 'cacheFileDisk')) {
            return $this->cacheFileDisk;
        }
        return $this->getFileConfig('cache-disk');
    }

    public function setCacheFileDisk($diskName)
    {
        $this->cacheFileDisk = $diskName;
        return $this;
    }

    public function getFileOwnerId()
    {
        if (property_exists($this, 'fileOwnerId')) {
            return $this->fileOwnerId;
        }
        return '0';
    }
    public function setFileOwnerId($ownerId)
    {
        //if id is zero, set as empty
        if ($ownerId == '0') {
            $ownerId = '';
        }
        $ownerId .= '/';
        $ownerId = Utils::normalizeFileNameUrl($ownerId);
        $this->fileOwnerId = $ownerId;
        return $this;
    }

    public function getFileDir()
    {
        return Utils::normalizeFileNameUrl(Utils::parseTemplateString($this->getFileConfig('folder'), $this->getFileTemplateVars()));
    }

    public function getCacheFileDir()
    {
        return Utils::normalizeFileNameUrl(Utils::parseTemplateString($this->getFileConfig('cache-folder'), $this->getFileTemplateVars()));
    }

    public function getMimeExtension()
    {
        return $this->getFileMimeType() ? Utils::mime2ext($this->getFileMimeType()) : pathinfo($this->_fileName, PATHINFO_EXTENSION);
    }

    public function getFileMimeType()
    {
        return $this->_fileMimeType;
    }

    public function setMimeType($mimeType)
    {
        $this->_fileMimeType = $mimeType;
        return $this;
    }

    public function getFileSize()
    {
        return $this->_fileSize;
    }

    public function setFilesize($size)
    {
        $this->_fileSize = $size;
        return $this;
    }

    public function getFileName()
    {
        return $this->_fileName;
    }


    public function setFileName($fileName)
    {
        $this->_fileName = $fileName;
        return $this;
    }

    public function getCompleteFilename()
    {
        return  Utils::normalizeFileNameUrl($this->getFileDir() . DIRECTORY_SEPARATOR . $this->getFileName());
    }

    public function getCompleteCacheFilename()
    {
        return  Utils::normalizeFileNameUrl($this->getCacheFileDir() . DIRECTORY_SEPARATOR . $this->getFileName());
    }

    public function fileExists()
    {
        if ($this->getFileName()) {
            return Storage::disk($this->getFileDisk())->exists($this->getCompleteFilename());
        }
        return false;
    }

    public function cacheFileExists()
    {
        if ($this->getFileName()) {
            Storage::disk($this->getCacheFileDisk())->exists($this->getCompleteCacheFilename());
        }
        return false;
    }

    public function deleteCacheFile()
    {
        if ($this->cacheFileExists()) {
            Storage::disk($this->getFileDisk())->delete($this->getCompleteFilename());
        }
    }

    public function deleteFile()
    {
        try {
            $this->deleteCacheFile();
            if ($this->fileExists()) {
                Storage::disk($this->getFileDisk())->delete($this->getCompleteFilename());
            }
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $ex) {
        }
        $this->setFileProperties(null, null, 0);
    }

    public function getFileContents()
    {
        if ($this->fileExists()) {
            return Storage::disk($this->getFileDisk())->get($this->getCompleteFilename());
        }
    }

    public function getCacheFileContents()
    {
        if ($this->cacheFileExists()) {
            return Storage::disk($this->getCacheFileDisk())->get($this->getCompleteCacheFilename());
        }
    }

    public function downloadFile($disposition = 'attachment')
    {
        if ($this->fileExists()) {
            @ob_end_clean();
            return Storage::disk($this->getFileDisk())->download($this->getCompleteFilename(), $this->getFileName(), $this->constructResponseHeaders($disposition));
        }
        abort(404, __("The requested file doesn't exist!"));
    }

    public function downloadCacheFile($disposition = 'attachment')
    {
        if ($this->fileExists()) {
            @ob_end_clean();
            return Storage::disk($this->getCacheFileDisk())->download($this->getCompleteCacheFilename(), $this->getFileName(), $this->constructResponseHeaders($disposition));
        }
        abort(404, __("The requested file doesn't exist!"));
    }

    public function getFileUrl()
    {
        return Storage::disk($this->getFileDisk())->url($this->getCompleteFilename());
    }

    public function getPrivateFileUrl($userId)
    {
        return Storage::disk($this->getFileDisk())->url($this->getCompleteFilename());
    }


    public function getCacheFileUrl()
    {
        return Storage::disk($this->getCacheFileDisk())->url($this->getCompleteCacheFilename());
    }

    public function getCachePrivateFileUrl($userId)
    {
        return Storage::disk($this->getCacheFileDisk())->url($this->getCompleteCacheFilename());
    }

    public function constructResponseHeaders($disposition = 'attachment')
    {
        $fileName = $this->title;
        $fileType = $this->getFileMimeType();
        return [
            'Content-Description' => $fileName,
            'Content-Type' => $fileType,
            'Content-Disposition' => $disposition . '; filename=' . $fileName
        ];
    }

    public function setFileProperties($fileName, $mimeType, $size)
    {
        $this->_fileName = $fileName;
        $this->_fileMimeType = $mimeType;
        $this->_fileSize = $size;
    }

    public function saveFileFromStream($contents, $filename = null, $autoDelete = true)
    {
        if ($contents) {
            if (!$filename) {
                $filename = $this->generateFilename();
            }
            return $this->saveFile($contents, $filename, $autoDelete);
        }
    }


    public function getFileExtension()
    {
        $extension = null;
        if ($this->getFileName()) {
            $extension = pathinfo($this->getFileName(), PATHINFO_EXTENSION);
        }
        return $extension;
    }

    public function generateFilename()
    {

        $extension = $this->getFileExtension();
        return Str::random(40) . ($extension ? ".{$extension}" : '');
    }

    public function processFileUpload($data)
    {
        if (is_object($data) && get_class($data) === UploadedFile::class) {
            $this->saveFile($data);
        } else {
            $data = collect($data);
            if ($data->get('file')) {
                $this->saveFile($data->get('file'));
            } else if ($data->get('temp')) {
                $this->saveFileFromStream(Storage::disk($this->getFileConfig('temp-disk'))->get($data->get('temp')), $data->get('temp'));
                Storage::disk($this->getFileConfig('temp-disk'))->delete($data->get('temp'));
            } else if (!$data->get('current')) {
                $this->deleteFile();
            }
        }
    }


    public function saveFile($file, $fileName = null, $autoDelete = true)
    {
        if ($file) {
            if (!$fileName) {

                if (method_exists($file, 'hashName')) {
                    $fileName = '';
                } else {
                    $fileName = $this->generateFilename();
                    //throw new \Exception('You must specify a filename to copy!');
                }
            }


            Storage::disk($this->getFileDisk())->makeDirectory($this->getFileDir());

            $finalName = Utils::normalizeFileNameUrl(Storage::disk($this->getFileDisk())->put($this->getFileDir() . DIRECTORY_SEPARATOR . $fileName, $file));

            if (!$fileName && $finalName) {
                $fileName = $finalName;
            } else {
                $fileName = $this->getFileDir() . DIRECTORY_SEPARATOR . $fileName;
            }
            $mimeType = Storage::disk($this->getFileDisk())->mimeType($fileName);
            $size = Storage::disk($this->getFileDisk())->size($fileName);
            if ($autoDelete) {
                $this->deleteFile();
            }

            $this->setFileProperties(basename($fileName), $mimeType, $size);
        } else {
            throw new \Exception("File not found!");
        }
    }
}
