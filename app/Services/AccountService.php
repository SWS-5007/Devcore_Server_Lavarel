<?php

namespace App\Services;

use App\Lib\Services\GenericService;
use App\Lib\Utils;
use App\Models\Company;
use App\Models\Token;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Validators\MyCompanyValidator;
use App\Validators\ProfileValidator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccountService
{

    private static $_instance = null;

    protected function __construct()
    {
    }

    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    public function getUser()
    {
        return config("app.user");
    }


    protected function saveImage($req, User $user)
    {
        if ($req->get('file')) {
            $user->saveImage($req->get('file'));
        } elseif ($req->get('delete_avatar')) {
            $user->deleteImage();
        }
    }


    protected function fillFromArray($user, $array)
    {
        $req = collect($array);
        $user->first_name = $req->get('first_name', $user->first_name);
        $user->last_name = $req->get('last_name', $user->last_name);
        $user->email = $req->get('email', $user->email);
        $user->phone = $req->get('phone', $user->phone);
        $user->lang = $req->get('lang', $user->lang);
        $user->notifications = $req->get('user_notifications', $user->notifications);

        if ($req->get('password') && $req->get('change_password')) {
            $user->password = Utils::encodePassword($req->get('password'));
        }
        return $user;
    }

    protected function validate($data, $object, $option)
    {

        if ($data == null) {
            throw new \Exception(trans('messages.element_null'));
        }

        return $this->getValidator($data, $object, $option)->execute();
    }

    /**
     *
     * @param type $data
     * @param type $object
     * @param type $option
     * @return ProfileValidator
     */
    protected function getValidator($data, $object, $option)
    {
        return (new ProfileValidator($data, $object, $option));
    }

    public function updateProfile($fields)
    {
        $user = $this->getUser();
        $this->validate($fields, $user, 'update');
        $user = $this->fillFromArray($user, $fields);
        $user->must_change_password = false;
        $this->saveImage(collect($fields), $user);
        $user->save();
        return User::find($user->id);
    }

    public function userLanguageUpdate($data)
    {
        $input = collect($data);
        $user = $this->getUser();

        if (!$user) {
            throw new NotFoundHttpException();
        }
        $user->lang = $input->get('lang');
        $user->save();
        return $user;
    }

    public function userDisplayScoreUpdate($data)
    {
        $input = collect($data);
        $user = $this->getUser();

        if (!$user) {
            throw new NotFoundHttpException();
        }
        $user->profile_score_display = $input->get('profile_score_display');
        $user->save();
        return $user;
    }

    public function userIdeaIntroUpdate($data)
    {
        $input = collect($data);
        $user = $this->getUser();

        if (!$user) {
            throw new NotFoundHttpException();
        }
        $user->profile_idea_intro = $input->get('profile_idea_intro');
        $user->save();
        return $user;
    }

    public function userProfileRewardUpdate($data)
    {
        $input = collect($data);
        $user = $this->getUser();

        if (!$user) {
            throw new NotFoundHttpException();
        }
        $user->profile_reward_active = $input->get('profile_reward_active');
        $user->save();
        return $user;
    }



    protected function getCompanyValidator($data, $object, $option)
    {
        return (new MyCompanyValidator($data, $object, $option));
    }

    protected function validateCompany($data, $object, $option)
    {

        if ($data == null) {
            throw new \Exception(trans('messages.element_null'));
        }

        return $this->getCompanyValidator($data, $object, $option)->execute();
    }


    public function updateCompany($fields)
    {
        $fields = collect($fields);
        $company = $this->getUser()->company;
        $this->validateCompany($fields, $company, 'update');
        $company->name = $fields->get('name', $company->name);
        $company->currency_code = $fields->get('currency_code', $company->currency_code);

        if ($fields->get('file')) {
            $company->saveImage($fields->get('file'));
        } elseif ($fields->get('delete_logo')) {
            $company->deleteImage();
        }
        $company->save();
        return Company::find($company->id);
    }

    public function requestResetPassword($fieldName, $fieldValue)
    {
        $user = User::where([$fieldName => $fieldValue])
            //->where('status', 'ACTIVE')
            ->first();
        if (!$user) {
            throw new NotFoundHttpException(__('auth.failed'));
        }

        $value = Str::random(6);
        $token = TokenService::instance()->create([
            'model_type' => 'user',
            'field_name' => $fieldName,
            'field_value' => $fieldValue,
            'purpose' => 'reset_password',
            'value' => $value,
            'expires_at' => Carbon::now()->addMinutes(config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')),
        ]);
        return $this->sendPasswordReset($value, $user);
    }

    protected function sendPasswordReset($tokenValue, $user)
    {
        return $user->notify((new ResetPasswordNotification($tokenValue))->locale($user->lang));
    }


    public function resetPassword($token, $username)
    {
        $validToken = TokenService::instance()->checkAndUseToken($token, 'reset_password', $username, 'user', 'email', false);
        if ($validToken) {
            return UserService::instance()->resetPassword($validToken->owner);
        } else {
            throw new BadRequestHttpException(__('Your token is invalid or has been expired!'));
        }
    }
}
