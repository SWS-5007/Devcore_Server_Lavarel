<?php

namespace App\Lib;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class Context
{
    public  $name = 'API';
    public  $user = null;
    public  $request = null;
    public  $response = null;
    public  $company = null;
    protected static $_current = null;

    public static function get($name = null)
    {
        if (!static::$_current) {
            static::$_current = new static();
        }
  //      Log::info(request()->get('current_context'));
//        if(request()){
//            Log::info("__________REQUEST_________");
//            Log::info(request());
//        }

        return static::$_current;

//        $_current = request()->get('current_context');
//        if (!$_current) {
//            $_current = new self();
//            request()->request->add(['current_context' => $_current]);
//        }
//        return  $_current;


        }

//        $_current = request()->get('current_context');
//        if (!$_current) {
//            $_current = new self();
//            request()->request->add(['current_context' => $_current]);
//        }
//        return  $_current;
//    }

    public function setUser($user)
    {
        $this->user = $user;
        if ($user) {
            $this->setCompany($user->company);
        }
    }

    public function getUser()
    {
        return $this->user;
    }

    public  function setCompany($company)
    {
        $this->company = $company;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getCompanyId()
    {
        return $this->company ? $this->company->id : null;
    }

    public function getUserId()
    {
        return $this->user ? $this->user->id : null;
    }

    public function getCompanyRole()
    {
        return $this->getUser() ? $this->getUser()->companyRole : null;
    }

    public function getCompanyRoleId()
    {
        return $this->getCompanyRole() ? $this->getCompanyRole()->id : null;
    }

    public function getLocale()
    {
        Lang::getLocale();
    }

    public function getRequest()
    {
        return $this->request ? $this->request : request();
    }
}
