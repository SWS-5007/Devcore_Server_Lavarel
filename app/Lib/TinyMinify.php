<?php

namespace App\Lib;

class TinyMinify
{
    static function html($html, $options = [])
    {
        $minifier = new HtmlMinifier($options);
        return $minifier->minify($html);
    }
}
