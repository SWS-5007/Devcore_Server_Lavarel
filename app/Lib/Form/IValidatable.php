<?php

namespace App\Lib\Form;

interface IValidatable
{
    function addRule($value);
    function getRules();
}
