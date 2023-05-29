<?php

namespace App\Lib\Form;

class Field extends Element implements IValidatable
{
    protected $dflValue = null;
    protected $printWrapper = true;
    protected $wrapper = null;
    protected $rules = [];

    function __construct($type = 'text', $dflValue = '', $name = '', $id = '', $class = '')
    {
        parent::__construct($type, $name, $id, $class);
        $this->template = __DIR__ . DIRECTORY_SEPARATOR . 'templates/input.blade.php';
        $this->dflValue = $dflValue;
        $this->value($dflValue);
        $this->wrapper = new Element('div', null, null, 'form-group');
        $this->wrapper->addChild($this);
    }

    public function addRule($value)
    {
        if ($value == 'required') {
            $this->required('required');
        }

        $this->rules[] = $value;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function getHtml($extraVars = [])
    {
        if (isset($extraVars['errors']) && count($extraVars['errors'])) {
            $this->addClass('is-invalid');
            $this->wrapperClass .= ' is-invalid';
        }

        $extraVars = array_merge($extraVars);
        $this->wrapper->content(parent::getHtml($extraVars));
        return $this->wrapper->getHtml();
    }

    public function reset()
    {
        return $this->value($this->dflValue);
    }
}
