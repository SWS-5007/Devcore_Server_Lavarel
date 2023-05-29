<?php

namespace App\Lib\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Lib\Utils;

class FileUploadController extends BaseController
{

    public function getConfigValue($section, $key)
    {
        return config('files.' . $section . '.' . $key, config('files.default.' . $key));
    }

    public function upload(Request $request, Response $response)
    {
        $parameters = preg_replace('#(/{2,})#', '/', $request->parameters);
        $paramArr = explode('/', $parameters);
        $sectionName = array_shift($paramArr);
        $id = implode('/', $paramArr);

        Storage::disk($this->getConfigValue($sectionName, 'disk'))->makeDirectory(Utils::parseTemplateString($this->getConfigValue($sectionName, 'folder'), [
            'section' => $sectionName,
            'folder' => $id,
        ]));

        $file = Storage::disk($this->getConfigValue($sectionName, 'disk'))->put(Utils::parseTemplateString($this->getConfigValue($sectionName, 'folder'), [
            'section' => $sectionName,
            'folder' => $id,
        ]),  $request->file('file'));


        exit();
    }

    public function uploadTemp(Request $request, Response $response)
    {
        //split parameters
        /*$parameters = preg_replace('#(/{2,})#', '/', $request->parameters);
        $paramArr = explode('/', $parameters);
        $sectionName = array_shift($paramArr);
        $id = implode('/', $paramArr);

        Storage::disk($this->getConfigValue($sectionName, 'temp-disk'))->makeDirectory(Utils::parseTemplateString($this->getConfigValue($sectionName, 'folder'), [
            'section' => $sectionName,
            'folder' => $id,
        ]));*/
        $file = Storage::disk($this->getConfigValue('default', 'temp-disk'))->put('/', $request->file('file'));


        return ['filename' => basename($file)];
    }
}
