<?php

namespace App\Lib\Form;

class ToggleButton extends Field
{
    protected $dflIsChecked;

    function __construct($type, $isChecked, $value, $name = '', $id = '', $class = '')
    {
        parent::__construct($type, $value, $name, $id, $class);
        $this->dflIsChecked = $isChecked;
        $this->checked($isChecked);
        $this->addClass('form-control');
    }

    public function checked($value){
        if($value){
            $this->setAttribute('checked', 'checked');
        }else{
            $this->removeAttribute('checked');
        }
    }

    public function reset()
    {
        return $this->checked($this->dflIsChecked);
    }
}
