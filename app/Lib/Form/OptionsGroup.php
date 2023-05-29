<?php

namespace App\Lib\Form;

class OptionsGroup extends Element
{
    protected $label;
    protected $items;

    public function __construct($label, $items = [])
    {
        $this->$label = $label;
        $this->template = __DIR__ . DIRECTORY_SEPARATOR . 'templates/options-group.blade.php';
    }

    public function addItem(Option $opt)
    {
        $this->items[] = $opt;
    }


    public function getHtml($extraVars = [])
    {
        $extraVars = array_merge($extraVars, ['label' => $this->label, 'items' => $this->items]);
        return parent::getHtml($extraVars);
    }
}
