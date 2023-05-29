<?php

namespace App\Console\Commands;

use App\GraphQL\Resolvers\CompanyRoleResolver;
use App\Lib\Models\Permissions\Role;
use App\Lib\Services\PermissionService;
use App\Lib\Services\RoleService;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class UpdateSuperAdminPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update:super:permissions';

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
        $this->info('Update Permissions of Super User');

        Model::unguard();




        $superUsers = UserService::instance()->find()->where('is_super_admin',1)->first();

        // foreach($superUsers as $user){
            $superUsers->assignRole('Super Admin');
        // }

        $this->info('Roles and permissions updated');
    }
}
