<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\CompanyRole;
use App\Models\Idea;
use App\Models\IdeaContent;
use App\Models\ModelHasRole;
use App\Models\IdeaIssue;
use App\Models\Process;
use App\Models\ProcessOperation;
use App\Models\ProcessStage;
use App\Models\ProcessPhase;
use App\Notifications\IdeasSummaryNotification;
use App\Services\UserService;
use App\Services\ProcessService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class PathDefaultPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:idea:pathdefaultpermissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets idea default content to idea description and attachment';

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
            $this->info(sprintf('Defaulting all company roles to all paths for: %s...', $company->name));
            $companyRoles = $company->companyRoles->all();

            foreach($company->processes as $process) {
                $this->info(sprintf('Defaulting all company roles to all paths for process: %s...', $process->title));
                $stages = $process->stages;
                $operations = $process->operations;
                $phases = $process->phases;
                foreach($stages as $stage) {
                    $this->info(sprintf('Syncing stage %s with all roles', $stage->title));
                    $stage->syncCompanyRoles($companyRoles);
                }
                foreach($operations as $operation) {
                    $this->info(sprintf('Syncing operation %s with all roles', $operation->title));
                    $operation->syncCompanyRoles($companyRoles);
                }
                foreach($phases as $phase) {
                    $this->info(sprintf('Syncing phase %s with all roles', $phase->title));
                    $phase->syncCompanyRoles($companyRoles);
                }
            }
        }
    }
}
