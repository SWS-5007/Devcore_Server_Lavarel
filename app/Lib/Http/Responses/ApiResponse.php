<?php
namespace App\Lib\Http\Responses;

use Illuminate\Http\Response;

class ApiResponse extends Response
{
    protected $payload = null;

    public function getPayload()
    {
        return $this->payload;
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;
    }

    public function render()
    {
        return response($this->payload);
    }
}
