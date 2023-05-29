<?php

namespace App\Lib\Form;

class Select extends Field
{
    protected $items = [];

    function __construct($dflValue = '', $name = '', $id = '', $class = '', $isMultiple = false)
    {
        parent::__construct('select', $dflValue, $name, $id, $class);
        $this->addClass('form-control');
        $this->multiple($isMultiple);
    }

    public function multiple($value)
    {
        if ($value) {
            $this->setAttribute("multiple", "multiple");
        } else {
            $this->removeAttribute("multiple");
        }
    }

    public function reset()
    {
        return $this->value($this->dflValue);
    }

    public function addOption(Option $opt) {
        $this->items[] = $opt;
    }

    public function addOptionsGroup(OptionsGroup $optGroup) {
        $this->items[] = $optGroup;
    }

    public function name($name) {
        if ($this->multiple && $name != '' && !preg_match('#\[\]$#', $name)) {
            $name.='[]';
        }
        return $name;
    }
}
