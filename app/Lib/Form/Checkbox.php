<?php

namespace App\Lib\Form;

class Checkbox extends ToggleButton
{
    function __construct($isChecked = false, $value = 1, $name = '', $id = '', $class = '')
    {
        parent::__construct('checkbox', $isChecked, $value, $name, $id, $class);
    }
}
