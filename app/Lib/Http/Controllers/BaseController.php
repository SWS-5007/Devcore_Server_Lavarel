<?php

namespace App\Lib\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Lib\Models\GuestUser;
use Illuminate\Support\Facades\Auth;
//use Creitive\Breadcrumbs\Facades\Breadcrumbs;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user = null;
    protected $guardName = '';
    protected $title = '';

    public function __construct($guardName = 'web', $user = null)
    {
        $this->user = $user;
        $this->guardName = $guardName;
        //Breadcrumbs::setCssClasses('breadcrumb');
        $this->addBreadCrumb('Home', config('home_url', '/'));
    }

    /**
     * 
     * @return \Modules\Base\Models\User
     */
    protected function getUser()
    {
        if ($this->user == null) {
            $this->user = $this->guard()->user();

            if (!$this->user) {
                $this->user = new GuestUser();
            } else {
                $this->user->guard_name = $this->guardName;
            }
        }
        return $this->user;
    }

    protected function guard()
    {
        return  Auth::guard($this->guardName);
    }

    protected function getRequestLatitude()
    {
        return is_numeric(request()->header('x-lat')) ? (float) request()->header('x-lat') : null;
    }

    protected function getRequestLongitude()
    {
        return is_numeric(request()->header('x-lng')) ? (float) request()->header('x-lng') : null;
    }

    protected function getRequestCurrency()
    {
        return request()->header('x-currency', 'USD');
    }

    protected function getRequestClient()
    {
        return request()->header('x-client', 'web');
    }


    protected function getRequestClientVersion()
    {
        return request()->header('x-version', '1.0');
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getTitle()
    {
        if (!$this->title) {
            if (request()->get('title')) {
                $this->title = request()->get('title');
            } else {
                $this->title = self::getTrans('generics.defaults.no_title');
            }
        }
        return $this->title;
    }

    function setTitle($title)
    {
        $this->title = $title;
    }

    public static function getTrans($key, $params=[])
    {
        return __($key, $params);
    }



    public function setFlash($type, $message, $bag = 'message')
    {
        session()->flash($bag, "$type|$message");
    }

    public function addBreadCrumb($title, $action = '')
    {
        //\Breadcrumbs::addCrumb($title,  $action);
    }

    public function resetBreadcrumb(){
        //\Breadcrumbs::removeAll();
    }

    public function addCurrentBreadCrumb()
    {
        $this->addBreadCrumb($this->getTitle(), \Request::url());
    }
    /**
     * Obtiene los datos básicos para la vista
     * @return type
     */
    public function getDataView()
    {
        $ret = array(
            'title' => $this->getTitle(),
            'user' => $this->getUser(),
        );
        return $ret;
    }
}
