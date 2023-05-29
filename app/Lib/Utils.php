<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lib;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

/**
 * Description of Utils
 *
 * @author pablo
 */
class Utils
{

    private static $_COMPARATORS = array(
        'eq' => array('comp' => '=', 'post' => "' "),
        'lt' => array('comp' => '<', 'post' => "' "),
        'gt' => array('comp' => '>', 'post' => "' "),
        'le' => array('comp' => '<=', 'post' => "' "),
        'ge' => array('comp' => '>=', 'post' => "' "),
        'cn' => array('comp' => 'LIKE', 'pre' => " '%", 'post' => "%' "),
        'nn' => array('comp' => 'IS NOT NULL'),
        'sw' => array('comp' => 'LIKE', 'post' => "%' "),
        'ew' => array('comp' => 'LIKE', 'pre' => " '%"),
    );

    public static function getQueryComparators()
    {
        return self::$_COMPARATORS;
    }

    public static function getQueryComparator($comp)
    {
        if (isset(self::$_COMPARATORS[$comp])) {
            return self::$_COMPARATORS[$comp];
        }
        return '=';
    }

    public static function addConditionToPaginator($paginator, $field, $comp, $value, $type = 'string')
    {

        $comparator = array('comp' => '=');
        $condition = $field;
        if (isset(self::$_COMPARATORS[$comp])) {
            $comparator = self::$_COMPARATORS[$comp];
        }

        switch ($type) {
            case 'date':
                $value = implode("-", array_reverse(explode("/", $value)));
                break;
            default:
                $value = $value;
        }
        $compClause = $comparator['comp'] . ((isset($comparator['pre'])) ? $comparator['pre'] : " '") . $value . ((isset($comparator['post'])) ? $comparator['post'] : '');
        $paginator->where($field, $compClause, $value);

        return $paginator;
    }

    public static function buildQueryCondition($field, $comp, $value, $type = 'string')
    {
        $comparator = array('comp' => '=');
        $condition = $field;
        if (isset(self::$_COMPARATORS[$comp])) {
            $comparator = self::$_COMPARATORS[$comp];
        }

        switch ($type) {
            case 'date':
                $value = implode("-", array_reverse(explode("/", $value)));
                break;
            default:
                $value = $value;
        }

        $condition .= ' ' . $comparator['comp'] . ((isset($comparator['pre'])) ? $comparator['pre'] : " '") . $value . ((isset($comparator['post'])) ? $comparator['post'] : '') . " ";
        //echo $condition;
        return $condition;
    }

    public static function encodePassword($password)
    {
        return bcrypt($password);
    }

    public static function base64Encode($value)
    {
        return base64_encode($value);
    }

    public static function base64Decode($value)
    {
        return base64_decode($value);
    }

    /**
     * Elimina los valores nulos del array
     * @param type $data
     */
    public static function clearData($array)
    {
        $ret = self::arrayFilter($array);
        print_r($ret);
        exit('llega');
        return $ret;
    }

    public static function removeEmtpyValues($haystack)
    {
        if (!is_array($haystack)) {
            return $haystack;
        }
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $haystack[$key] = self::removeEmtpyValues($haystack[$key]);
            }

            if (empty($haystack[$key])) {
                unset($haystack[$key]);
            }
        }

        return $haystack;
    }

    static function array_remove_null($item)
    {
        if (!is_array($item)) {
            return $item;
        }

        return collect($item)
            ->reject(function ($item) {
                return is_null($item);
            })
            ->flatMap(function ($item, $key) {
                return is_numeric($key) ? [self::array_remove_null($item)] : [$key => self::array_remove_null($item)];
            })
            ->toArray();
    }


    public static function isAdminApp()
    {
        return request()->is('admin') || request()->is('admin/*');
    }

    public static function isRouteUrl($name)
    {
        return request()->is($name) || request()->is($name . '/*');
    }

    public static function isRoute($name)
    {
        return \Route::currentRouteName() === $name;
    }

    public static function isRouteRegex($name)
    {
        return preg_match('#' . $name . '.([a-z]+)#', \Route::currentRouteName());
    }

    public static function getAppNameSlug()
    {
        return Str::slug(config('app.name'), '_');
    }


    public static function getLocalizedCacheKey($key, $lang = null)
    {
        if (!$lang) {
            $lang = Lang::locale();
        }
        return config('website_id') . '_' . $lang . '_' . $key;
    }

    public static function clearLocalizedCache($key, $lang = null)
    {
        if (!$lang) {
            $lang = Lang::locale();
        }
        Cache::forget(self::getLocalizedCacheKey($key, $lang));
    }

    public static function clearLocalizedCacheForAllLangs($key)
    {
        $locales = self::getAvailableLangs();
        foreach ($locales as $langKey => $lang) {
            self::clearLocalizedCache($key, $langKey);
        }
    }

    public static function getAvailableLangs()
    {
        return config("app.available_locales");
        // $locales = \LaravelLocalization::getSupportedLocales();
        // return $locales;
    }

    // requires nicmart/string-template
    private static $engine = null;
    public static function parseTemplateString($string, $vars = [])
    {
        if (!self::$engine) {
            self::$engine = new \StringTemplate\Engine;
        }

        return self::$engine->render($string, $vars);
    }


    public static function getRandomColor($seed = null)
    {

        $colors = config('custom.colors');
        $colorKeys = array_keys($colors);
        $colorKey = null;
        if (!$seed) {
            $colorKey = Arr::random($colorKeys);
        } else {
            $index = $seed % count($colorKeys);
            $colorKey = $colorKeys[$index];
        }


        return ['color' => $colorKey, 'contrast' => $colors[$colorKey]];
    }

    public static function saveFileFromUrlToTemp($url)
    {

        $file = self::downloadFileFromUrl($url);
        if ($file) {
            $temp_file_name = tempnam(sys_get_temp_dir(), Str::uuid()->toString());
            return file_put_contents($temp_file_name, $file) ? $temp_file_name : null;
        }
        return null;
    }

    public static function fileToBase64($file)
    {
        $base64 = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getPathname()));
        //return base64_encode($file);
        return $base64;
    }


    public static function downloadFileFromUrl($url)
    {
        return file_get_contents($url);
    }

    public static function getGravatarUrl($strToHash, $type = 'robohash', $size = '500')
    {
        return 'https://www.gravatar.com/avatar/' . md5($strToHash) . '?s=' . $size . '&d=' . $type;
    }

    public static function generateGravatar($strToHash, $type = 'identicon', $size = '500')
    {
        return self::saveFileFromUrlToTemp(self::getGravatarUrl($strToHash, $type, $size));
    }

    public static function getInitialAvatarUrl($str,  $size = 500, $background = "000000", $color = "ffffff")
    {
        $background = str_replace('#', '', $background);
        $color = str_replace('#', '', $color);
        $str = urlencode($str);
        return "https://ui-avatars.com/api/?name={$str}&background={$background}&color={$color}&size={$size}&font-size=0.5";
    }

    public static function generateInitialAvatar($str, $size = 500, $background = null, $color = '#ffffff')
    {
        print_r(self::getInitialAvatarUrl($str, $size, $background, $color));
        exit();
        return self::saveFileFromUrlToTemp(self::getInitialAvatarUrl($str, $size, $background, $color));
    }

    public static function getDateFromRequest($value)
    {
        try {
            return Carbon::createFromFormat('Y-m-d', $value);
        } catch (\Exception $ex) {
            return Carbon::createFromFormat('m/d/Y', $value);
        }
    }

    public static function mime2ext($mime)
    {
        $mime_map = [
            'video/3gpp2'                                                               => '3g2',
            'video/3gp'                                                                 => '3gp',
            'video/3gpp'                                                                => '3gp',
            'application/x-compressed'                                                  => '7zip',
            'audio/x-acc'                                                               => 'aac',
            'audio/ac3'                                                                 => 'ac3',
            'application/postscript'                                                    => 'ai',
            'audio/x-aiff'                                                              => 'aif',
            'audio/aiff'                                                                => 'aif',
            'audio/x-au'                                                                => 'au',
            'video/x-msvideo'                                                           => 'avi',
            'video/msvideo'                                                             => 'avi',
            'video/avi'                                                                 => 'avi',
            'application/x-troff-msvideo'                                               => 'avi',
            'application/macbinary'                                                     => 'bin',
            'application/mac-binary'                                                    => 'bin',
            'application/x-binary'                                                      => 'bin',
            'application/x-macbinary'                                                   => 'bin',
            'image/bmp'                                                                 => 'bmp',
            'image/x-bmp'                                                               => 'bmp',
            'image/x-bitmap'                                                            => 'bmp',
            'image/x-xbitmap'                                                           => 'bmp',
            'image/x-win-bitmap'                                                        => 'bmp',
            'image/x-windows-bmp'                                                       => 'bmp',
            'image/ms-bmp'                                                              => 'bmp',
            'image/x-ms-bmp'                                                            => 'bmp',
            'application/bmp'                                                           => 'bmp',
            'application/x-bmp'                                                         => 'bmp',
            'application/x-win-bitmap'                                                  => 'bmp',
            'application/cdr'                                                           => 'cdr',
            'application/coreldraw'                                                     => 'cdr',
            'application/x-cdr'                                                         => 'cdr',
            'application/x-coreldraw'                                                   => 'cdr',
            'image/cdr'                                                                 => 'cdr',
            'image/x-cdr'                                                               => 'cdr',
            'zz-application/zz-winassoc-cdr'                                            => 'cdr',
            'application/mac-compactpro'                                                => 'cpt',
            'application/pkix-crl'                                                      => 'crl',
            'application/pkcs-crl'                                                      => 'crl',
            'application/x-x509-ca-cert'                                                => 'crt',
            'application/pkix-cert'                                                     => 'crt',
            'text/css'                                                                  => 'css',
            'text/x-comma-separated-values'                                             => 'csv',
            'text/comma-separated-values'                                               => 'csv',
            'application/vnd.msexcel'                                                   => 'csv',
            'application/x-director'                                                    => 'dcr',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
            'application/x-dvi'                                                         => 'dvi',
            'message/rfc822'                                                            => 'eml',
            'application/x-msdownload'                                                  => 'exe',
            'video/x-f4v'                                                               => 'f4v',
            'audio/x-flac'                                                              => 'flac',
            'video/x-flv'                                                               => 'flv',
            'image/gif'                                                                 => 'gif',
            'application/gpg-keys'                                                      => 'gpg',
            'application/x-gtar'                                                        => 'gtar',
            'application/x-gzip'                                                        => 'gzip',
            'application/mac-binhex40'                                                  => 'hqx',
            'application/mac-binhex'                                                    => 'hqx',
            'application/x-binhex40'                                                    => 'hqx',
            'application/x-mac-binhex40'                                                => 'hqx',
            'text/html'                                                                 => 'html',
            'image/x-icon'                                                              => 'ico',
            'image/x-ico'                                                               => 'ico',
            'image/vnd.microsoft.icon'                                                  => 'ico',
            'text/calendar'                                                             => 'ics',
            'application/java-archive'                                                  => 'jar',
            'application/x-java-application'                                            => 'jar',
            'application/x-jar'                                                         => 'jar',
            'image/jp2'                                                                 => 'jp2',
            'video/mj2'                                                                 => 'jp2',
            'image/jpx'                                                                 => 'jp2',
            'image/jpm'                                                                 => 'jp2',
            'image/jpeg'                                                                => 'jpg',
            'image/pjpeg'                                                               => 'jpg',
            'application/x-javascript'                                                  => 'js',
            'application/json'                                                          => 'json',
            'text/json'                                                                 => 'json',
            'application/vnd.google-earth.kml+xml'                                      => 'kml',
            'application/vnd.google-earth.kmz'                                          => 'kmz',
            'text/x-log'                                                                => 'log',
            'audio/x-m4a'                                                               => 'm4a',
            'audio/mp4'                                                                 => 'm4a',
            'application/vnd.mpegurl'                                                   => 'm4u',
            'audio/midi'                                                                => 'mid',
            'application/vnd.mif'                                                       => 'mif',
            'video/quicktime'                                                           => 'mov',
            'video/x-sgi-movie'                                                         => 'movie',
            'audio/mpeg'                                                                => 'mp3',
            'audio/mpg'                                                                 => 'mp3',
            'audio/mpeg3'                                                               => 'mp3',
            'audio/mp3'                                                                 => 'mp3',
            'video/mp4'                                                                 => 'mp4',
            'video/mpeg'                                                                => 'mpeg',
            'application/oda'                                                           => 'oda',
            'audio/ogg'                                                                 => 'ogg',
            'video/ogg'                                                                 => 'ogg',
            'application/ogg'                                                           => 'ogg',
            'application/x-pkcs10'                                                      => 'p10',
            'application/pkcs10'                                                        => 'p10',
            'application/x-pkcs12'                                                      => 'p12',
            'application/x-pkcs7-signature'                                             => 'p7a',
            'application/pkcs7-mime'                                                    => 'p7c',
            'application/x-pkcs7-mime'                                                  => 'p7c',
            'application/x-pkcs7-certreqresp'                                           => 'p7r',
            'application/pkcs7-signature'                                               => 'p7s',
            'application/pdf'                                                           => 'pdf',
            'application/octet-stream'                                                  => 'pdf',
            'application/x-x509-user-cert'                                              => 'pem',
            'application/x-pem-file'                                                    => 'pem',
            'application/pgp'                                                           => 'pgp',
            'application/x-httpd-php'                                                   => 'php',
            'application/php'                                                           => 'php',
            'application/x-php'                                                         => 'php',
            'text/php'                                                                  => 'php',
            'text/x-php'                                                                => 'php',
            'application/x-httpd-php-source'                                            => 'php',
            'image/png'                                                                 => 'png',
            'image/x-png'                                                               => 'png',
            'application/powerpoint'                                                    => 'ppt',
            'application/vnd.ms-powerpoint'                                             => 'ppt',
            'application/vnd.ms-office'                                                 => 'ppt',
            'application/msword'                                                        => 'doc',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'application/x-photoshop'                                                   => 'psd',
            'image/vnd.adobe.photoshop'                                                 => 'psd',
            'audio/x-realaudio'                                                         => 'ra',
            'audio/x-pn-realaudio'                                                      => 'ram',
            'application/x-rar'                                                         => 'rar',
            'application/rar'                                                           => 'rar',
            'application/x-rar-compressed'                                              => 'rar',
            'audio/x-pn-realaudio-plugin'                                               => 'rpm',
            'application/x-pkcs7'                                                       => 'rsa',
            'text/rtf'                                                                  => 'rtf',
            'text/richtext'                                                             => 'rtx',
            'video/vnd.rn-realvideo'                                                    => 'rv',
            'application/x-stuffit'                                                     => 'sit',
            'application/smil'                                                          => 'smil',
            'text/srt'                                                                  => 'srt',
            'image/svg+xml'                                                             => 'svg',
            'application/x-shockwave-flash'                                             => 'swf',
            'application/x-tar'                                                         => 'tar',
            'application/x-gzip-compressed'                                             => 'tgz',
            'image/tiff'                                                                => 'tiff',
            'text/plain'                                                                => 'txt',
            'text/x-vcard'                                                              => 'vcf',
            'application/videolan'                                                      => 'vlc',
            'text/vtt'                                                                  => 'vtt',
            'audio/x-wav'                                                               => 'wav',
            'audio/wave'                                                                => 'wav',
            'audio/wav'                                                                 => 'wav',
            'application/wbxml'                                                         => 'wbxml',
            'video/webm'                                                                => 'webm',
            'audio/x-ms-wma'                                                            => 'wma',
            'application/wmlc'                                                          => 'wmlc',
            'video/x-ms-wmv'                                                            => 'wmv',
            'video/x-ms-asf'                                                            => 'wmv',
            'application/xhtml+xml'                                                     => 'xhtml',
            'application/excel'                                                         => 'xl',
            'application/msexcel'                                                       => 'xls',
            'application/x-msexcel'                                                     => 'xls',
            'application/x-ms-excel'                                                    => 'xls',
            'application/x-excel'                                                       => 'xls',
            'application/x-dos_ms_excel'                                                => 'xls',
            'application/xls'                                                           => 'xls',
            'application/x-xls'                                                         => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
            'application/vnd.ms-excel'                                                  => 'xlsx',
            'application/xml'                                                           => 'xml',
            'text/xml'                                                                  => 'xml',
            'text/xsl'                                                                  => 'xsl',
            'application/xspf+xml'                                                      => 'xspf',
            'application/x-compress'                                                    => 'z',
            'application/x-zip'                                                         => 'zip',
            'application/zip'                                                           => 'zip',
            'application/x-zip-compressed'                                              => 'zip',
            'application/s-compressed'                                                  => 'zip',
            'multipart/x-zip'                                                           => 'zip',
            'text/x-scriptzsh'                                                          => 'zsh',
        ];

        return isset($mime_map[$mime]) ? $mime_map[$mime] : false;
    }

    public static function compressHtml($html)
    {
        return TinyMinify::html($html);
    }

    public static function normalizeFileNameUrl($folder)
    {
        return preg_replace('#/{2,}#', '/', $folder);
    }

    public static function getYoutubePreview($url, $withDefault = true)
    {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            $video_id = $match[1];
            return 'http://img.youtube.com/vi/' . $video_id . '/0.jpg';
        } else {
            return $withDefault ? asset('images/cv-video-default.jpg') : null;
        }
    }
}
