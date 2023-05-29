<?php
namespace App\Lib\Models;
use Spatie\ResponseCache\Facades\ResponseCache;

trait ClearsResponseCacheTrait
{
    public static function bootClearsResponseCacheTrait()
    {
        self::created(function () {
            ResponseCache::clear();
        });

        self::updated(function () {
            ResponseCache::clear();
        });

        self::deleted(function () {
            ResponseCache::clear();
        });
    }
}