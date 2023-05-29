<?php

namespace App\Lib\Storage;

use Illuminate\Support\Facades\Storage;

class StorageUtils
{
    public static function deleteFromDisk($diskName, $regex, $folder = '')
    {
        $files = (Storage::disk($diskName)->allFiles($folder));
        $regex = '#' . $regex . '#';
        $matchFiles = preg_grep($regex, $files);
        
        foreach ($matchFiles as $file) {
            Storage::disk($diskName)->delete($file);
        }
    }
}
