<?php

namespace App\Console\Commands;

use App\Lib\Services\RoleService;
use App\Models\Company;
use App\Models\CompanyRole;
use App\Models\Idea;
use App\Models\ModelHasRole;
use App\Models\IdeaIssue;
use App\Models\Process;
use App\Models\ProcessOperation;
use App\Models\ProcessStage;
use App\Models\ProcessPhase;
use App\Notifications\IdeasSummaryNotification;
use App\Services\IdeaService;
use App\Services\UserService;
use App\Services\ProcessService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class IdeaDefaultPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:idea:defaultpermissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets all idea permission roles to those defined in process path';

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
        $ideas = Idea::all();
        foreach ($ideas as $idea) {
            $this->info(sprintf('Defaulting idea roles from path: %s...', $idea->title));


          //  if(!$idea->company_role_ids || empty($idea->company_role_ids['0'])){
                $parent = $idea->parent();
                $pathWithRoles = $parent->with('companyRoles')->first();

                $roleIds = [];

                $this->info(sprintf('Setting all path roles to idea'));
                if ($idea->company_tool_id) {
                    $this->info(sprintf('Adding role: %s...', $idea->company_tool_id));
                    $toolIds[] = $idea->company_tool_id;
                    $idea->company_tool_ids = $toolIds;
                }
                $this->info(sprintf('Setting all path roles to idea'));
                Log::info(json_encode($pathWithRoles->companyRoles));
                foreach ($pathWithRoles->companyRoles as $role) {
                    $roleIds[] = (string) $role->id;
                    $this->info(sprintf('Adding role: %s...', $role->id));
                }
                //$service = IdeaService::instance()->find()->where('id', )->first();

                $idea->company_role_ids = $roleIds;
                $idea->save();
            };
     //   }
    }
}
