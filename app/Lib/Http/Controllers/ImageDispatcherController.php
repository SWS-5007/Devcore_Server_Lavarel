<?php

namespace App\Lib\Http\Controllers;

error_reporting(0);

use App\Lib\Storage\Image;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Lib\Utils;


class ImageDispatcherController extends BaseController
{


    /* use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;*/

    public function isValidImage($imageFile)
    {

        if (is_readable($imageFile)) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = mime_content_type($imageFile);
            if (in_array($contentType, $allowedMimeTypes)) {
                return $contentType;
            }
        }
        return false;
    }


    public function getConfigValue($section, $key)
    {
        return config('images.' . $section . '.' . $key, config('images.default.' . $key));
    }

    public function getFilesConfigValue($section, $key)
    {
        $fileConfigKey = Utils::parseTemplateString($this->getConfigValue($section, 'file-config-key'), [
            'section' => $section,
        ]);
        $defaultFileConfigKey = Utils::parseTemplateString($this->getConfigValue($section, 'file-config-key'), [
            'section' => 'default',
        ]);
        return config($fileConfigKey . '.' . $key, config($defaultFileConfigKey . '.' . $key));
    }

    public function dispatchImage(\Illuminate\Http\Request $request)
    {
        if (extension_loaded('gd') && function_exists('gd_info')) {
        } else {
            abort(500, "PHP GD library is NOT installed on your web server");
        }
        //get parameters
        $parameters = preg_replace('#(/{2,})#', '/', $request->parameters);
        //split parameters
        $paramArr = explode('/', $parameters);

        //min params are section - size - filename (we can omit the id parameter when the model owner id is 0 or empty)
        if (count($paramArr) < 3) {
            abort(404);
        }

        //the section name is the first parameter
        $sectionName = array_shift($paramArr);

        //the filenames is the last parameter
        $fileName = array_pop($paramArr);
        $thumb = array_pop($paramArr);

        //check if ignore cache
        $ignoreCache = isset($_GET['no-cache']);

        //image quality
        $imageQuality = 93;


        //the folder (id) is the rest of the array (replace dots by slashes)
        $id = str_replace('.', DIRECTORY_SEPARATOR, implode('/', $paramArr));


        //validate the section
        if (!array_key_exists($sectionName, config('images'))) {
            abort(404);
        }

        $generateDefault = ['generate' => $request->get("generate", "default")]; // $this->getConfigValue($sectionName, 'generate-defaults');

        if ($request->get("generate", "text") === "text") {
            $generateDefault["text"] = $request->get('text');
            $generateDefault["background"] = $request->get('background', '000000');
            $generateDefault["color"] = $request->get('color', 'FFFFFF');
        }


        $image = new Image();
        $image->setFileConfigKey($sectionName);
        $image->setFileSection($sectionName);
        $image->setFileName($fileName);
        $image->setFileOwnerId($id);

        return $image->resize($thumb, $generateDefault, $imageQuality, $generateDefault);
    }
}
