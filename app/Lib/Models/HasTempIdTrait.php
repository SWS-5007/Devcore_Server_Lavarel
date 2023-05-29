<?php

namespace App\Lib\Models;

use Illuminate\Support\Str;

trait HasTempIdTrait
{
    protected $_tempId=null;

    public function generateTempId()
    {
        $this->_tempId = 'temp_' . (string) Str::uuid();
    }

    public function tempId()
    {
        if (!$this->getKey()) {
            if (!$this->_tempId) {
                $this->generateTempId();
            }
            return $this->_tempId;
        }
        return $this->getKey();
    }

    public function assignTempId($value)
    {
        $this->_tempId = $value;
    }
}
