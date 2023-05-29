<?php
namespace App\Lib\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Lib\Models\GuestUser;
use Illuminate\Support\Facades\Auth;

class ApiBaseController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user = null;
    protected $guardName = '';

    public function __construct($guardName = 'api', $user = null)
    {
        $this->user = $user;
        $this->guardName = $guardName;
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
                //$this->user ->guard_name = $this->guard;
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
        return is_numeric(request()->header('x-lat')) ? (float)request()->header('x-lat') : null;
    }

    protected function getRequestLongitude()
    {
        return is_numeric(request()->header('x-lng')) ? (float)request()->header('x-lng') : null;
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
}
