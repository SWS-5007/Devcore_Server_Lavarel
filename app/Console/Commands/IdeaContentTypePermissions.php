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

class IdeaContentTypePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:idea:contenttypepermissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets all idea content permission roles';

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

        $companies = Company::all();

        foreach ($companies as $company) {
            $companyRoles = $company->companyRoles->all();
            $ideas = $company->ideas->all();

            foreach($ideas as $idea) {
                $contents = $idea->ideaContent;
                foreach ($contents as $content) {
                    $content->syncCompanyRoles($companyRoles);
            }
            }
        }

 //       $ideas = Idea::all();



//        foreach ($ideas as $idea) {
//            $this->info(sprintf('Defaulting idea roles from path: %s...', $idea->title));
//            $companyRoles = $company->companyRoles->all();
//            $contents = $idea->ideaContent;
//
//            $parent = $idea->parent();
//            $pathWithRoles = $parent->with('companyRoles')->first();
//
//            foreach ($pathWithRoles->companyRoles as $role) {
//                $roleIds[] = (string) $role->id;
//                $this->info(sprintf('Adding role: %s...', $role->id));
//            }
//
//            foreach ($contents as $content) {
//                 $content->syncCompanyRoles($pathWithRoles)
//            }
//
//        };
    }
}
