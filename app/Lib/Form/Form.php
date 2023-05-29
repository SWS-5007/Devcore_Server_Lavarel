<?php

namespace App\Lib\Form;

use Illuminate\Database\Eloquent\Collection;

class Form extends Element
{
    protected $fields = [];
    protected $ids_counter = 0;
    protected $sentData = null;
    protected $submitted;
    protected $validator = null;
    protected $rules = [];
    protected $errors = null;

    function __construct($name, $action = '', $method = 'GET', $id = '', $class = '', $enctype = '')
    {
        parent::__construct(null, $name, $id, $class);
        $this->template = __DIR__ . DIRECTORY_SEPARATOR . 'templates/form.blade.php';
        $this->sentData = new Collection();
        $this->name($name);
        $this->id((!empty($id) ? $id : $name));
        $this->action($action);
        $this->method($method);
        $this->enctype($enctype);
        $this->validator = null;
        $this->submitted = false;
        $this->errors = new Collection();
        $this->getSentData();
    }

    public function getNormailizedMethod()
    {
        return strtoupper($this->method);
    }

    private function getSentData()
    {
        $submitData = ($this->getNormailizedMethod() == 'GET') ? $_GET : $_POST;
        $formSubmitted = isset($submitData['_frm']) ? $submitData['_frm'] : null;
        if ($formSubmitted == $this->name) {
            $this->submitted = true;
            $this->loadSentData();
        }
    }


    private function loadSentData()
    {
        $this->sentData = new Collection(($this->getNormailizedMethod() == 'GET') ? $_GET : $_POST);
    }

    public function reset()
    {
        foreach ($this->fields as $field) {
            $field->reset();
        }
        $this->sentData = new Collection();
    }




    public function addElement(Element $field, $id = null)
    {
        if (!$id) {
            if ($field->id) {
                $id = $field->id;
            } else {
                $id = strtolower($field->getClass() . '_' . $this->ids_counter);
                $this->ids_counter++;
            }
        }

        if (!$field->name) {
            $field->name($id);
        }

        //if (isset($this->sentData[$field->name])) {
        switch (strtolower($field->type)) {
            case 'select':
                $name = str_replace('[]', '', $field->name);
                $value = isset($this->sentData[$name]) ? $this->sentData[$name] : ($this->submited ? '' : $field->dflValue);
                $field->value($value);
                break;
            case 'checkbox':
            case 'radio':
                if ($this->submitted) {
                    $checked = false;
                    if (isset($this->sentData[$field->name])) {
                        $checked = ($this->sentData[$field->name] == $field->value) ? true : false;
                    } else {
                        $checked = false;
                    }
                    $field->checked($checked);
                }
                break;
            default:
                $value = isset($this->sentData[$field->name]) ? $this->sentData[$field->name] : $field->dflValue;
                $field->value($value);
        }
        //}

        $this->fields[$id] = $field;
        return $this;
    }

    public function getField($id)
    {
        if (isset($this->fields[$id])) {
            return $this->fields[$id];
        }
        return null;
    }

    public function hasField($id)
    {
        return $this->getField($id) != null;
    }

    public function setValidator($validator)
    {
        $this->validator = $validator;
        return $this;
    }

    public function getValidator()
    {
        return $this->validator;
    }

    public function addRule($id, $rule)
    {
        if (is_string($rule)) {
            $rule = explode('|', $rule);
        }
        if (isset($this->fields[$id]) && $this->fields[$id] instanceof IValidatable) {
            foreach ($rule as $r) {
                $this->fields[$id]->addRule($r);
            }
        }
    }

    public function getRules()
    {
        $ret = [];
        foreach ($this->fields as $k => $f) {
            if ($f instanceof IValidatable && is_array($f->getRules()) && count($f->getRules())) {
                $ret[$f->name] = $f->getRules();
            }
        }
        return $ret;
    }

    public function validate($messages = [], $customAttributes = [])
    {
        if (!$this->validator) {
            $this->validator = validator($this->sentData->toArray(), $this->getRules(), $messages, $customAttributes);
        }
        $this->errors = $this->validator->messages();
        return !$this->validator->fails();
    }

    public function open($render = false)
    {
        // Extract the variables to a local namespace
        extract($this->attributes);
        extract(['attributes' => $this->attributes, 'errors' => $this->errors]);
        // Start output buffering
        ob_start();
        // Include the template file
        include $this->template;

        // End buffering and return its contents
        $output = ob_get_clean();

        if ($render) {
            echo $output;
        } else {
            return $output;
        }
    }

    public function close($render = false)
    {
        $output = '</form>';
        if ($render) {
            echo $output;
        } else {
            return $output;
        }
    }

    public function renderField($key, $print = true)
    {
        if ($this->getField($key)) {
            $field = $this->getField($key);
            if ($print) {
                $field->render(['errors' => collect($this->errors->get($field->name))]);
            } else {
                return $field->getHtml(['errors' => collect($this->errors->get($field->name))]);
            }
        }
    }

    public function getHtml($extraVars = [])
    {
        $output = null;
        // Extract the variables to a local namespace
        extract($this->attributes);
        extract(['attributes' => $this->attributes]);
        // Start output buffering
        ob_start();

        $this->open(true);

        foreach ($this->fields as $key => $field) {
            $this->renderField($field, true);
        }

        $this->close(true);

        // End buffering and return its contents
        $output = ob_get_clean();

        return $output;
    }

    public function render($extraVars = [])
    {
        echo $this->getHtml($extraVars);
    }
}
