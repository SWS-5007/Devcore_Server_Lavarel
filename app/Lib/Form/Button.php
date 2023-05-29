<?php

namespace App\Lib\Form;

class Button extends Element
{
    protected $dflValue = null;
    protected $text;

    function __construct($text, $type = "submit", $id = '', $class = '')
    {
        parent::__construct($type, null, $id, $class);
        $this->template = __DIR__ . DIRECTORY_SEPARATOR . 'templates/button.blade.php';
        $this->text($text);
    }

    public function getHtml($extraVars = [])
    {
       return parent::getHtml(array_merge($extraVars, ['text'=>$this->text]));
    }

}
