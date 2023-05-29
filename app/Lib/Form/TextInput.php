<?php

namespace App\Lib\Form;

class TextInput extends Field
{

    function __construct($dflValue = '', $name = '', $id = '', $class = '')
    {
        parent::__construct('text', $dflValue, $name, $id, $class);
        $this->addClass('form-control');
    }

    public function reset()
    {
        return $this->value($this->dflValue);
    }
}
