<?php

namespace App\Lib\Storage;

use App\Lib\Utils;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait ImageTrait
{
    use FileTrait;


    public function getImageConfigValue($section, $key)
    {
        return config('images.' . $section . '.' . $key, config('images.default.' . $key));
    }


    public function getOriginalImageUrl()
    {
        return $this->getImageUrl('0x0', false);
    }

    public function getCompleteCacheFilename()
    {
        if (func_num_args() != 1) {
            throw new \Exception('You must specify the cache image size!');
        }
        return  Utils::normalizeFileNameUrl($this->getCacheFileDir() . DIRECTORY_SEPARATOR . func_get_arg(0) . DIRECTORY_SEPARATOR . $this->getFileName());
    }


    public function cacheFileExists()
    {
        if (func_num_args() != 1) {
            throw new \Exception('You must specify the cache image size!');
        }

        if ($this->getFileName()) {
            Storage::disk($this->getCacheFileDisk())->exists($this->getCompleteCacheFilename(func_get_arg(0)));
        }
        return false;
    }

    public function getCacheFileUrl()
    {
        if (func_num_args() != 1) {
            throw new \Exception('You must specify the cache image size!');
        }
        return Storage::disk($this->getCacheFileDisk())->url($this->getCompleteCacheFilename(func_get_arg(0)));
    }

    public function getCachePrivateFileUrl($userId)
    {
        if (func_num_args() != 2) {
            throw new \Exception('You must specify the cache image size!');
        }
        return Storage::disk($this->getCacheFileDisk())->url($this->getCompleteCacheFilename(func_get_arg(1)));
    }

    public function deleteCacheFile()
    {
        StorageUtils::deleteFromDisk($this->getCacheFileDisk(), '/*/' . $this->getFileName(), $this->getCacheFileDir());
    }

    public function getImageUrl($size = '0x0', $ignoreCache = false)
    {
        if (!$ignoreCache) {
            if ($this->cacheFileExists($size)) {
                return $this->getCacheFileUrl($size);
            }
        }
        $url = route(Config::get('images.dispatcher.name'), ['parameters' => Utils::normalizeFileNameUrl($this->getFileSection() . '/' . $this->getFileOwnerId() . '/' . $size . '/' .  $this->getFileName())]);
        $extraParams = [];
        if ($ignoreCache) {
            $extraParams[] = 'no-cache=1';
        }
        if (count($extraParams)) {
            $url .= '?' . implode('&', $extraParams);
        }
        return $url;
    }

    public function validateThumbSize($thumb = '0x0')
    {
        //validate the thumb param
        if (!preg_match('/^([0-9]+)([x_-])([0-9]+)$/', $thumb, $size)) {
            return false;
        }

        //filter the thumbs sizes
        $validThums = $this->getImageConfigValue($this->getFileSection(), 'valid-thumbs');
        if (is_array($validThums) && count($validThums)) {
            if (!in_array($thumb, $validThums)) {
                return false;
            }
        }

        return $size;
    }

    public function validateFolder()
    {
        //filter folders
        $valid = false;
        $validFolders = $this->getImageConfigValue($this->getFileSection(), 'valid-folders');
        //return $id;

        if (is_string($validFolders) && $this->getFileOwnerId()) {
            $valid = preg_match($validFolders, $this->getFileOwnerId());
        } else if (is_array($validFolders) && count($validFolders) && $this->getFileOwnerId()) {
            $i = 0;
            while (!$valid && $i < count($validFolders)) {
                $valid = preg_match($validFolders[$i], $this->getFileOwnerId());
                $i++;
            }
        } else {
            $valid = true;
        }

        return $valid;
    }

    public function validateMimeType($mimeType)
    {
        //check if mime is valid
        $allowedMimes = $this->getImageConfigValue($this->getFileSection(), 'allowed-types');
        if (is_string($allowedMimes)) {
            $allowedMimes = explode(',', $allowedMimes);
        }
        $validMime = false;
        if (is_array($allowedMimes) && count($allowedMimes)) {
            $i = 0;
            while (!$validMime && $i < count($allowedMimes)) {
                $validMime = preg_match($allowedMimes[$i], $mimeType);
                $i++;
            }
        } else {
            $validMime = true;
        }

        return $validMime;
    }

    public function resize($thumb = '0x0', $generateDefault = ['generate' => 'default'], $imageQuality = 100, $sendResponse = false)
    {

        if (!$this->validateFolder()) {
            if ($sendResponse) {
                abort(404, 'File not found');
            }
            throw new FileConfigException('The requested folder is invalid!');
        }
        $size = $this->validateThumbSize($thumb);
        if (!$size) {
            if ($sendResponse) {
                abort(404, 'File not found');
            }
            throw new FileConfigException('The requested size is invalid!');
        }

        //get widht and height
        $requestWidth = (int) $size[1];
        $requestHeight = (int) $size[3];
        //min dimensions
        if ($requestWidth < 15 && $requestWidth != 0)
            $requestWidth = 15;
        if ($requestHeight < 15 && $requestHeight != 0)
            $requestHeight = 15;


        $fill = false;
        $noCrop = false;
        if ($size[2] == '-') {
            $noCrop = true;
        } elseif ($size[2] == '_') {
            $fill = true;
            $noCrop = true;
            $fillWidth = $requestWidth;
            $fillHeight = $requestHeight;
        }

        $originalStorage = Storage::disk($this->getFileDisk());
        $cacheStorage = Storage::disk($this->getCacheFileDisk());
        $originalFileName = $this->getCompleteFilename();
        $mimeType = null;

        if (!$this->fileExists()) {

            if ($generateDefault) {

                if ($generateDefault["generate"] === "text" && isset($generateDefault["text"])) {
                    if (!isset($generateDefault["background"])) {
                        $color = Utils::getRandomColor();
                        $generateDefault["background"] = $color['color'];
                        $generateDefault["color"] = $color['contrast'];
                    };


                    if (!isset($generateDefault["color"])) {
                        $generateDefault['color'] = "FFFFFF";
                    }

                    $originalStorage->put($originalFileName, file_get_contents(Utils::getInitialAvatarUrl($generateDefault["text"], 500, $generateDefault["background"], $generateDefault['color'])));
                } else {
                    //get the default filename
                    $originalStorage = Storage::disk('defaults');
                    $defaultImageName = $this->getImageConfigValue($this->getFileSection(), 'not-found-image');
                    $originalFileName = Utils::normalizeFileNameUrl(Utils::parseTemplateString($this->getImageConfigValue($this->getFileSection(), 'defaults-folder'), [
                        'section' => $this->getFileSection(),
                        'folder' => $this->getFileOwnerId(),
                        'thumb' => $thumb,
                    ]) . DIRECTORY_SEPARATOR . $defaultImageName);


                    if (!$originalStorage->exists($originalFileName)) {
                        $originalFileName = Utils::normalizeFileNameUrl($this->getImageConfigValue('default', 'not-found-image'));
                        if (!$originalStorage->exists($originalFileName)) {
                            return null; //the default image doesn't exist
                        }
                    }
                }
                $mimeType = $originalStorage->mimeType($originalFileName);
            } else {
                if ($sendResponse) {
                    abort(404, 'File not found');
                }
                return null;
            }
        } else {
            $mimeType = $originalStorage->mimeType($originalFileName);

            if (!$this->validateMimeType($mimeType)) {
                if ($sendResponse) {
                    abort(404, 'File not found');
                }
                return null;
            }
        }

        $png = $mimeType == 'image/png';
        $this->setMimeType($mimeType);
        $originalImageContents = $originalStorage->get($originalFileName);
        if (!$originalImageContents) {
            if ($sendResponse) {
                abort(404, 'File not found');
            }
            return null;
        }

        //original image requested
        if ($requestWidth == 0 && $requestHeight == 0) {
            $cacheStorage->put($this->getCompleteCacheFilename($thumb), $originalImageContents);
            if ($sendResponse) {
                @ob_end_clean();
                return $cacheStorage->response($this->getCompleteCacheFilename($thumb));
            }
            return $cacheStorage->get($this->getCompleteCacheFilename($thumb));
        }

        $originalImage = imagecreatefromstring($originalImageContents);

        if (!$originalImage) {
            if ($sendResponse) {
                abort(404, 'File not found');
            }
            return null;
        }
        $originalImageContents = null; //deletes the original image contents to free memory

        // get the original image dimensions
        $originalWidth = imagesx($originalImage);
        $originalHeight = imagesy($originalImage);

        // calculate relation of original and requested images
        $rRel = 0;
        if ($requestHeight > 0 && $requestWidth > 0) {
            $rRel = ($requestWidth / $requestHeight);
        }

        $oRel = ($originalWidth / $originalHeight);
        $isWide = $rRel < $oRel;
        //
        if ($noCrop) {
            // determine the original aspect ratio
            if ($isWide) {
                if ($originalWidth < $requestWidth) {
                    $requestWidth = $originalWidth;
                }
                $requestHeight = 0;
            } else {
                if ($originalHeight < $requestHeight) {
                    $requestHeight = $originalHeight;
                }
                $requestWidth = 0;
            }
        }

        //calculate dimensions
        if ($requestWidth == 0) {
            $requestWidth = round($originalWidth * ($requestHeight / $originalHeight));
        } elseif ($requestHeight == 0) {
            $requestHeight = round($originalHeight * ($requestWidth / $originalWidth));
        }


        //calculate proportions
        if ($isWide) {
            $srcHeight = $originalHeight;
            $srcWidth = ($requestWidth / $requestHeight) * $originalHeight;
            $srcPositionX = ($originalWidth - $srcWidth) / 2;
            $srcPositionY = 0;
        } else {
            $srcWidth = $originalWidth;
            $srcHeight = ($requestHeight / $requestWidth) * $originalWidth;
            $srcPositionX = 0;
            $srcPositionY = ($originalHeight - $srcHeight) / 5;
        }

        // create the final image
        $finalImage = imagecreatetruecolor($requestWidth, $requestHeight);
        @ob_end_clean();
        ob_start();
        if ($png) {
            imagealphablending($finalImage, false);
            imagesavealpha($finalImage, true);
            imagecopyresampled($finalImage, $originalImage, 0, 0, (int) $srcPositionX, (int) $srcPositionY, $requestWidth, $requestHeight, (int) $srcWidth, (int) $srcHeight);
            imagepng($finalImage);
        } else {
            if (!$fill) {
                imagecopyresampled($finalImage, $originalImage, 0, 0, (int) $srcPositionX, (int) $srcPositionY, $requestWidth, $requestHeight, (int) $srcWidth, (int) $srcHeight);
                imagejpeg($finalImage, null, $imageQuality);
            } else {
                $fillImage = imagecreatetruecolor($fillWidth, $fillHeight);
                imagefilledrectangle($fillImage, 0, 0, ($fillWidth), ($fillHeight), imagecolorallocate($fillImage, 255, 255, 255));
                imagecopyresampled($fillImage, $originalImage, (int) (max(1, ($fillWidth - $requestWidth) / 2)), (int) (max(1, ($fillHeight - $requestHeight) / 2)), 0, 0, $requestWidth, $requestHeight, $srcWidth - 1, $srcHeight - 1);
                imagejpeg($fillImage, null, $imageQuality);
                imagedestroy($fillImage);
            }
        }
        imagedestroy($finalImage);
        imagedestroy($originalImage);

        $image_contents = ob_get_clean();

        //save the image to chace
        $cacheStorage->put($this->getCompleteCacheFilename($thumb), $image_contents);
        if ($sendResponse) {
            //print the contents
            @ob_end_clean();
            header('Content-Type: ' . $mimeType);
            echo $image_contents;
            exit();
        } else {
            return $image_contents;
        }
    }
}
