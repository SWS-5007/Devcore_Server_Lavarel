<?php

namespace App\Lib\Form;

class Element
{
    protected $attributes = [];
    protected $template = null;
    protected $childElements = [];
    protected $content = '';

    public function __construct($type, $name = '', $id = '', $class = '')
    {
        $this->setAttribute('type', $type);
        $this->setAttribute('name', $name);
        $this->setAttribute('id', $id);
        $this->setAttribute('class', $class);
        $this->template = __DIR__ . DIRECTORY_SEPARATOR . 'templates/element.blade.php';
    }

    public function addClass($name)
    {
        return $this->setAttribute('class', trim($this->getAttribute('class') . ' ' . $name));
    }

    public function removeClass($name)
    {
        $classes = explode(" ", $this->getAttribute('class', ' '));
        if (isset($classes[$name])) {
            unset($classes[$name]);
        }
        $this->setAttribute('class', trim(implode(' ', $classes)));
    }

    public function getAttribute($name, $default = null)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        return $default;
    }

    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    public function removeAttribute($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            unset($this->attributes[$name]);
        }

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return $this->$name($arguments);
        } elseif (property_exists($this, $name)) {
            return $this->$name = $arguments[0];
        }
        return $this->setAttribute($name, $arguments[0]);
    }

    public function __get($key)
    {
        if (method_exists($this, $key)) {
            return $this->$key();
        } elseif (property_exists($this, $key)) {
            // Getter/Setter not defined so return property if it exists
            return $this->$key;
        }
        return $this->getAttribute($key);
    }

    public function __set($key, $value)
    {
        if (method_exists($this, $key)) {
            return $this->$key($value);
        } else {
            return $this->setAttribute($key, $value);
        }
    }

    public function addChild(Element $element)
    {
        $this->childElements[] = $element;
    }

    public function getHtml($extraVars = [])
    {
        $output = null;
        // Extract the variables to a local namespace
        extract($this->attributes);
        extract(array_merge(['attributes' => $this->attributes, 'childs' => $this->childElements, 'content' => $this->content], $extraVars));
        // Start output buffering
        ob_start();

        // Include the template file
        include $this->template;

        // End buffering and return its contents
        $output = ob_get_clean();

        return $output;
    }

    public function render($extraVars = [])
    {
        echo $this->getHtml($extraVars);
    }

    public function getClass()
    {
        $classNameWithNamespace = get_class($this);
        return substr($classNameWithNamespace, strrpos($classNameWithNamespace, '\\') + 1);
    }
}
