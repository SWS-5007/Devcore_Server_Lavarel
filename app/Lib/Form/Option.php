<?php

namespace App\Lib\Form;

class Option extends Element
{
    protected $label;

    public function __construct($value, $label, $selected = false)
    {
        $this->value($value);
        $this->$label = $label;
        $this->selected($selected);
        $this->template = __DIR__ . DIRECTORY_SEPARATOR . 'templates/option.blade.php';
    }

    public function selected($value)
    {
        if ($value) {
            $this->setAttribute('selected', 'selected');
        } else {
            $this->removeAttribute('selected');
        }
    }

    public function getHtml($extraVars = [])
    {
        $extraVars = array_merge($extraVars, ['label' => $this->label]);
        return parent::getHtml($extraVars);
    }
}
