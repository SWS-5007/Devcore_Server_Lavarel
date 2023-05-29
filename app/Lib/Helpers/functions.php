<?php

/**
 * Recupera de $arr el valor del la clave $key
 * Si no existe retorna vacio o cero;
 *
 * @param string $name
 * @param array $arr
 * @param string $type
 * @return mixed
 */
function getValue($key, $arr, $type = 'text', $default = null)
{
    if (isset($arr[$key])) {
        $value = $arr[$key];
        if ($type == 'text') {
            $value = trim($value);
        } elseif ($type == 'int') {
            $value = (int) $value;
        } elseif ($type == 'bool' || $type == 'boolean') {
            $value = (int) $value;
        } elseif ($type == 'float' || $type == 'double') {
            $value = floatval($value);
        } elseif ($type == 'DateTime') {
            $value = $value == '' || preg_match('#^[0:\- ]+$#', $value) ? null : new DateTime($value);
        } elseif ($type == 'noTrim') {
            //$value = $value;
        } else {
            $value = trim($value);
        }
    } else {
        if ($default != null) {
            return $default;
        }
        if ($type == 'text') {
            $value = '';
        } elseif ($type == 'int') {
            $value = 0;
        } elseif ($type == 'bool' || $type == 'boolean') {
            $value = false;
        } elseif ($type == 'float' || $type == 'double') {
            $value = 0;
        } elseif ($type == 'DateTime') {
            $value = new DateTime();
        } else {
            $value = '';
        }
    }
    return $value;
}

function replace_qs($name, $value)
{
    return \App\Lib\Helpers\Helpers::replaceCurrentQs($name, $value);
}

function generateRoutes($baseRouteUrl, $baseRouteName, $controllerName, $paramName = 'id', $except = ['update', 'show', 'store'])
{
    Route::resource($baseRouteUrl, $controllerName, ['except' => $except, 'names' => [
        'create' => $baseRouteName . '.create',
        'update' => $baseRouteName . '.update',
        'edit' => $baseRouteName . '.edit',
        'show' => $baseRouteName . '.show',
        'store' => $baseRouteName . '.store',
        'destroy' => $baseRouteName . '.destroy',
        'index' => $baseRouteName . '.index',
    ]]);
    Route::post($baseRouteUrl . '/create', array('as' => $baseRouteName . '.create', 'uses' => $controllerName . '@create')); //para que acepte el post
    Route::put($baseRouteUrl . '/{' . $paramName . '}/edit', array('as' => $baseRouteName . '.edit', 'uses' => $controllerName . '@edit')); //para que acepte el put
}

function generateRoutesModule($baseRouteUrl, $baseRouteName, $controllerName, $paramName = 'id', $except = ['update', 'show', 'store'])
{
    generateRoutes($baseRouteUrl, $baseRouteName, '\App\Modules\\' . $controllerName, $paramName, $except);
}

function tidyHtml($html, $docType = 'HTML 4.01 Transitional')
{
    $html = str_replace(array('&nbsp;'), '', $html);
    return $html;
    $config = HTMLPurifier_Config::createDefault();
    // configuration goes here:
    $config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
    $config->set('HTML.Doctype', $docType); // replace with your doctype
    $config->set('AutoFormat.AutoParagraph', true); // replace with your doctype
    $config->set('AutoFormat.RemoveEmpty', true);
    $config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
    $config->set('HTML.Allowed', 'a[rel|name|href|target|title],
    strong,b,em,i,strike,u,h1,h2,h3,h4,h5,h6,
    p[align],ol[type|compact],ul,li,br,img[src|width|height|alt|title],
    table[border|cellspacing|cellpadding|width|align|summary|bgcolor|style],
    tr,tbody,thead,tfoot,
    td[colspan|rowspan|width|height|align|valign|bgcolor]
    th[colspan|rowspan|width|height|align|valign]');

    $purifier = new HTMLPurifier($config);
    //site::addToDebug($purifier->purify($html));
    return $purifier->purify($html);
}

function getPlainText($html)
{
    $text = str_replace("  ", " ", html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8'));
    return str_replace(['!!mediaplayer!!'], '', $text);
}

function hideLineBreaks($text)
{
    return trim(str_replace(["  ", "\t", "/\s+/"], "", preg_replace("/\r|\n/", " ", $text)));
}


function siteProtocol()
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
}
