<?php

namespace App\Services;

use App\Lib\Models\Permissions\Role;
use App\Lib\Utils;
use App\Models\ModelHasRole;
use App\Models\User;
use App\Notifications\PasswordResettedNotification;
use App\Notifications\UserCreatedNotification;
use App\Notifications\UserInvitedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Lib\Validators\GenericValidator;
use App\Validators\UserValidator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(User::class, false);
    }

    public static function instance()
    {

        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    protected function fillFromArray($option, $data, $instance)
    {

        $instance = parent::fillFromArray($option, $data, $instance);
        $data = collect($data);
        $instance->first_name = $data->get('first_name', $instance->first_name);
        $instance->last_name = $data->get('last_name', $instance->last_name);
        $instance->email = $data->get('email', $instance->email);
        $instance->phone = $data->get('phone', $instance->phone);
        $instance->company_role_id = $data->get('company_role_id', $instance->company_role_id);
        $instance->yearly_costs = $data->get('yearly_costs', $instance->yearly_costs);
        $instance->company_id = $data->get('company_id', $instance->company_id);
        $instance->lang = $data->get('lang', $instance->lang);
        $instance->notifications = true;

        if ($data->get('password')) {
            $instance->password = Utils::encodePassword($data->get('password'));
            $instance->must_change_password = true;
        }

        if ($data->get('profile_score_display')) {
            $instance->profile_score_display = $data->get('profile_score_display', $instance->profile_score_display);
        }

        return $instance;
    }

    protected function assignableRoles()
    {
        if ($this->getUser()->is_super_admin) {
            return Role::all();
        }
        $roles = Role::where("name", "!=", "Super Admin");

        if ($this->getUser()->principalRoleName() === "Company Admin") {
            return $roles->get();
        }

        if ($this->getUser()->principalRoleName() === "Company Manager") {
            return $roles->where("name", "!=", "Company Admin")->get();
        }
        return collect([]);
    }

    protected function getValidator($data, $object, $option)
    {
        return new UserValidator($data, $object, $option);
    }

    protected function syncRoles($object, $roles)
    {
        $assignableRoles = $this->assignableRoles();

        if ($roles && $assignableRoles->isNotEmpty()) {
            if (!is_array($roles)) {
                $roles = [$roles];
            }

            $roles = array_filter($roles, function ($id) use ($assignableRoles) {
                return $assignableRoles->contains('id', $id);
            });

            if (count($roles)) {
                $object->syncRoles($roles);
            } else {
                $object->syncRoles($this->assignableRoles()->first()->id);
            }
        }
    }

    protected function syncCompanyRoles($object, $roles)
    {
        return $object;
    }

    protected function creating($data, $object)
    {
        $this->syncRoles($object, collect($data)->get('role_id'));
        $this->syncCompanyRoles($object, collect($data)->get('company_role_id'));
        return $object;
    }

    protected function updating($data, $object)
    {
        $this->syncRoles($object, collect($data)->get('role_id'));
        $this->syncCompanyRoles($object, collect($data)->get('company_role_id'));
        return $object;
    }

    protected function deleted($object)
    {
        return $object;
    }

    protected function beforeUpdate($data, $object){
        $updaterCompanyUsers = User::where(['company_id' => $object->company_id]);
        $anotherAdmin = [];
        foreach ($updaterCompanyUsers->get() as $user){
            if($user->principalRoleName() === "Company Admin" && $user->id !== $object->id){
                $anotherAdmin[] = $user->id;
            };
        }

        $isUpdatingAdmin = $object->principalRoleName() === 'Company Admin';

        if(!$isUpdatingAdmin) {
            if(count($anotherAdmin) < 1){
                throw new BadRequestHttpException("Company must have atleast one person with company admin priviledges.");
            }
        }

        return $object;
    }

    protected function beforeDelete($object)
    {
        $toBeRemovedRole = ModelHasRole::where('model_id',$object->id)->first();
        $removerRoleId = $this->getUser()->principalRoleId();

        if($this->getUser()->id === $object->id){
            throw new BadRequestHttpException("You can not remove your own account");
        }

        if($toBeRemovedRole->role_id < $removerRoleId){
            throw new BadRequestHttpException("Can not remove user with higher permissions");
        }
        $removerCompanyUsers = User::where(['company_id' => $object->company_id]);
        $anotherAdmin = [];
        foreach ($removerCompanyUsers->get() as $user){
            if($user->principalRoleName() === "Company Admin"){
                $anotherAdmin[] = $user;
            };
        }
        if(count($anotherAdmin) < 1){
            throw new BadRequestHttpException("Please appoint another admin before deleting admin user");
        }

        return $object;
    }



    protected function afterCreate($data, $object)
    {
        $object = parent::created($data, $object);
        $user = $this->getUser();
        $object->notify((new UserCreatedNotification($data, $object, $user))->locale($object->lang));
        return $object;
    }


    protected function prepareData($option, &$data, &$object)
    {

        if ($option === 'create' &&  !isset($data['password'])) {
            $data['password'] = Str::random(10);
        }
    }


    public function resetPassword($user, $notify = true)
    {
        $newPassword = Str::random(10);
        $user->must_change_password = true;
        $user->password = Utils::encodePassword($newPassword);
        $user->save();

        if ($notify) {
            $user->notify((new PasswordResettedNotification($newPassword))->locale($user->lang));
        }

        return ['user' => $user, 'password' => $newPassword];
    }


    public function invite($user, $args, $notify = true)
    {
        $user->notify((new UserInvitedNotification($args['input']['project_id']))->locale($user->lang));
        $projectInst = ProjectService::instance();
        $projectInst->addUser($args['input']['project_id'], $user->id);
    }
}
