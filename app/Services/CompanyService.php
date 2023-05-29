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
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompanyService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {

        parent::__construct(Company::class, false);
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



    protected function validate($data, $object, $option)
    {

        if ($data == null) {
            throw new \Exception(trans('messages.element_null'));
        }
        return true;
        return $this->getValidator($data, $object, $option)->execute();
    }

    /**
     *
     * @param type $data
     * @param type $object
     * @param type $option
     * @return ProfileValidator
     */
    // protected function getValidator($data, $object, $option)
    // {
    //     return (new ProfileValidator($data, $object, $option));
    // }



    // protected function getCompanyValidator($data, $object, $option)
    // {
    //     return (new MyCompanyValidator($data, $object, $option));
    // }

    // protected function validateCompany($data, $object, $option)
    // {

    //     if ($data == null) {
    //         throw new \Exception(trans('messages.element_null'));
    //     }

    //     return $this->getCompanyValidator($data, $object, $option)->execute();
    // }


    public function createCompany($fields)
    {
        $fields = collect($fields);
        $adminEmail = $fields->get('adminEmail', '');

        // Check User Email Exists....
        $user = UserService::instance()->find()
            ->where('email',$adminEmail)
            ->first();

        $company = null;

        if($user==null):
            $company = new Company();

            // $this->validateCompany($fields, $company, 'update');
            $company->name = $fields->get('name', '');
            $company->currency_code = $fields->get('currency_code', '');
            $company->default_lang = config("app.lang");

            if ($fields->get('file')) {
                $company->saveImage($fields->get('file'));
            } elseif ($fields->get('delete_logo')) {
                $company->deleteImage();
            }

            if($company->save()):
                    // Create Company Role
                    $companyRole = CompanyRoleService::instance()->create(
                        [
                            'name' => config("app.default_company_role","Management"),
                            'company_id' =>  $company->id,
                        ]
                    );
                    // ...
                    if($companyRole!=null):
                        // Create User....
                        $user = UserService::instance()
                        ->create([
                                    'email'=>$adminEmail,
                                    'first_name'=>$this->getAdminFirstName($company,$adminEmail),
                                    'last_name'=>$this->getAdminLastName($company,$adminEmail),
                                    'company_id' => $company->id,
                                    'lang' => $fields->get('lang'),
                                    'role_id' => config("app.default_role","2"),
                                    'company_role_id'=>$companyRole->id,
                                    // 'password'=>config("app.default_pwd","test12"),
                                    'yearly_costs'=> 0,
                                ]);
                    endif;
            endif;
        else:
            throw new BadRequestHttpException(__("User with this email address already exists, please select other email address."));
        endif;
        return ($company);
    }

    private function getAdminFirstName($company,$email){
        return $company->name;
    }

    private function getAdminLastName($company,$email){
        return 'Administrator';
    }

    public function updateCompany($fields)
    {
        $fields = collect($fields);
        $company = CompanyService::instance()->find()
                        ->where('id', $fields['id'])
                        ->first();
        if($company!=null):
            $company->name = $fields->get('name', '');
            $company->currency_code = $fields->get('currency_code', '');
            $company->default_lang = 'en';

            if ($fields->get('file')) {
                $company->saveImage($fields->get('file'));
            } elseif ($fields->get('delete_logo')) {
                $company->deleteImage();
            }
            $company->save();
            return $company;
        else:
            return null;
        endif;
    }

    public function deleteCompany($fields)
    {

        $fields = collect($fields);
        $company = CompanyService::instance()->find()
                        ->where('id', $fields['id'])
                        ->first();

        if($company!=null):
            return $company->delete();
        else:
            return false;
        endif;
    }


}
