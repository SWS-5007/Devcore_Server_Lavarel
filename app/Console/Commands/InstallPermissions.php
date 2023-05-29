<?php

namespace App\Console\Commands;

use App\GraphQL\Resolvers\CompanyRoleResolver;
use App\Lib\Models\Permissions\Role;
use App\Lib\Services\PermissionService;
use App\Lib\Services\RoleService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class InstallPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install or update the needed permissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Creating roles and permissions');
        Artisan::call("permission:cache-reset", [], $this->output);
        Model::unguard();

        $roleNames = ['Super Admin', 'Company Admin', 'Company Manager', 'User'];
        foreach ($roleNames as $name) {
            $role = RoleService::instance()->find()->where('name', $name)->first();
            if (!$role) {
                $role = Role::create([
                    'name' => $name,
                    'guard_name' => 'web'
                ]);
            }
        }

        $roles = RoleService::instance()->find()->get();
        $admin = $roles->where('name', 'Company Admin')->first();
        $manager = $roles->where('name', 'Company Manager')->first();
        $user = $roles->where('name', 'User')->first();
        $superUser = $roles->where('name', 'Super Admin')->first();
        $service = PermissionService::instance();

        $permissionNames = ['create', 'update', 'delete', 'read', 'readAll', 'manage'];

        //industries
        $industryPermissionNames = array_merge($permissionNames, []);
        $moduleName = "core/industry/";
        foreach ($industryPermissionNames as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);
            $superUser->givePermissionTo($permCompleteName);
            if ($pName === 'read' || $pName == 'readAll' || $pName = 'create') {
                $admin->givePermissionTo($permCompleteName);
                $manager->givePermissionTo($permCompleteName);
                $user->givePermissionTo($permCompleteName);
            }
        }

        //currencies
        $currencyPermissionName = array_merge($permissionNames, []);
        $moduleName = "core/currency/";
        foreach ($currencyPermissionName as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);
            $superUser->givePermissionTo($permCompleteName);
            if ($pName === 'read' || $pName == 'readAll' || $pName = 'create') {
                $admin->givePermissionTo($permCompleteName);
                $manager->givePermissionTo($permCompleteName);
                $user->givePermissionTo($permCompleteName);
            }
        }

        //tools
        $toolsPermissionName = array_merge($permissionNames, []);
        $moduleName = "core/tool/";
        foreach ($toolsPermissionName as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);
            $superUser->givePermissionTo($permCompleteName);
            if ($pName === 'read' || $pName == 'readAll' || $pName = 'create') {
                $admin->givePermissionTo($permCompleteName);
                $manager->givePermissionTo($permCompleteName);
                $user->givePermissionTo($permCompleteName);
            }
        }

        //engage
        $engagePermissionName = array_merge($permissionNames, []);
        $moduleName = "core/support/engage/";
        foreach ($engagePermissionName as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);
            $superUser->givePermissionTo($permCompleteName);
            if ($pName === 'read' || $pName == 'readAll' || $pName = 'create') {
                $admin->givePermissionTo($permCompleteName);
                $manager->givePermissionTo($permCompleteName);
                $user->givePermissionTo($permCompleteName);
            }
        }

        //ideaIssueReply
        $ideaIssueReply = array_merge($permissionNames, []);
        $moduleName = "core/ideaIssueReply/";
        foreach ($ideaIssueReply as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);
            $superUser->givePermissionTo($permCompleteName);
            if ($pName === 'read' || $pName == 'readAll' || $pName = 'create') {
                $admin->givePermissionTo($permCompleteName);
                $manager->givePermissionTo($permCompleteName);
                $user->givePermissionTo($permCompleteName);
            }
        }

        //issue effect
        $issueEffectPermissionName = array_merge($permissionNames, []);
        $moduleName = "support/issueEffect/";
        foreach ($issueEffectPermissionName as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);
            $superUser->givePermissionTo($permCompleteName);
            if ($pName === 'read' || $pName == 'readAll' || $pName = 'create') {
                $admin->givePermissionTo($permCompleteName);
                $manager->givePermissionTo($permCompleteName);
                $user->givePermissionTo($permCompleteName);
            }
        }

        //issue
        $issueEffectPermissionName = array_merge($permissionNames, []);
        $moduleName = "support/issue/";
        foreach ($issueEffectPermissionName as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);
            $superUser->givePermissionTo($permCompleteName);
            if ($pName === 'read' || $pName == 'readAll' || $pName = 'create') {
                $admin->givePermissionTo($permCompleteName);
                $manager->givePermissionTo($permCompleteName);
                $user->givePermissionTo($permCompleteName);
            }
        }

        //roles
        $rolePermissionNames = array_merge($permissionNames, []);
        $moduleName = "auth/role/";
        foreach ($rolePermissionNames as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);
            $superUser->givePermissionTo($permCompleteName);

            if ($pName === 'read' || $pName == 'readAll') {
                $admin->givePermissionTo($permCompleteName);
                $manager->givePermissionTo($permCompleteName);
                $user->givePermissionTo($permCompleteName);
            }
        }

        //users
        $userPermissionNames = array_merge($permissionNames, ['reset_password', 'edit_my_company']);
        $moduleName = "auth/user/";
        foreach ($userPermissionNames as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);

            if ($pName == 'manage' || $pName == 'edit_my_company') {
                $admin->givePermissionTo($permCompleteName);
            } else {
                $admin->givePermissionTo($permCompleteName);
                $manager->givePermissionTo($permCompleteName);
            }

            if ($pName === 'read' || $pName == 'readAll') {
                $user->givePermissionTo($permCompleteName);
            }
        }

        //companyRoles
        $companyRolePermissionNames = array_merge($permissionNames, []);
        $moduleName = "core/companyRole/";
        foreach ($companyRolePermissionNames as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);
            // $superUser->givePermissionTo($permCompleteName);
            if ($pName !== 'manage') {
                $admin->givePermissionTo($permCompleteName);
                $manager->givePermissionTo($permCompleteName);
            } else {
                $admin->givePermissionTo($permCompleteName);
            }

            if ($pName == 'read' || $pName == 'readAll') {
                $user->givePermissionTo($permCompleteName);
            }
        }


        //tool
        $companyToolPermissionNames = array_merge($permissionNames, []);
        $moduleName = "core/companyTool/";
        foreach ($companyToolPermissionNames as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);
            $admin->givePermissionTo($permCompleteName);
            $manager->givePermissionTo($permCompleteName);
            $superUser->givePermissionTo($permCompleteName);
            if ($pName === 'read' || $pName == 'readAll') {
                $user->givePermissionTo($permCompleteName);
            }
        }

        //process
        $processPermissionNames = array_merge($permissionNames, []);
        $moduleName = "process/process/";
        foreach ($processPermissionNames as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);
            $admin->givePermissionTo($permCompleteName);
            $manager->givePermissionTo($permCompleteName);
            $superUser->givePermissionTo($permCompleteName);

            if ($pName === 'read' || $pName == 'readAll') {
                $user->givePermissionTo($permCompleteName);
            }
        }

        $ideaPermissionNames = array_merge($permissionNames, []);
        $moduleName = "improve/idea/";
        foreach ($ideaPermissionNames as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);
            $admin->givePermissionTo($permCompleteName);
            $manager->givePermissionTo($permCompleteName);
            $superUser->givePermissionTo($permCompleteName);

            if ($pName !== 'delete') {
                $user->givePermissionTo($permCompleteName);
            }
        }


        $projectPermissionNames = array_merge($permissionNames, []);
        $moduleName = "core/reply/";
        foreach ($projectPermissionNames as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);
            $admin->givePermissionTo($permCompleteName);
            $manager->givePermissionTo($permCompleteName);
            $superUser->givePermissionTo($permCompleteName);

            if ($pName === 'read' || $pName == 'readAll') {
                $user->givePermissionTo($permCompleteName);
            }
        }

        $projectPermissionNames = array_merge($permissionNames, []);
        $moduleName = "core/project/";
        foreach ($projectPermissionNames as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);
            $admin->givePermissionTo($permCompleteName);
            $manager->givePermissionTo($permCompleteName);
            $superUser->givePermissionTo($permCompleteName);

            if ($pName === 'read' || $pName == 'readAll') {
                $user->givePermissionTo($permCompleteName);
            }
        }

        //company
        $processPermissionNames = array_merge($permissionNames, []);
        $moduleName = "core/company/";
        foreach ($processPermissionNames as $pName) {
            $permCompleteName = $moduleName . $pName;
            $service->registerOperations($permCompleteName);
            $superUser->givePermissionTo($permCompleteName);
        }

        $this->info('Roles and permissions created');
    }
}
