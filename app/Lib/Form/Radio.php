<?php

namespace App\Lib\Form;

class Radio extends ToggleButton
{
    function __construct($isChecked = false, $value = 1, $name = '', $id = '', $class = '')
    {
        parent::__construct('radio', $isChecked, $value, $name, $id, $class);
    }
}
