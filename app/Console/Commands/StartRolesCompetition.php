<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\CompanyRole;
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

class StartRolesCompetition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:roles:compete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set roles against each other';

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
        foreach($companies as $company){
            $rolesByUserScore = $company->companyRoles()->WithUserScores()->get()->sortBy("user_engage_scores");
            foreach($rolesByUserScore as $role){
                if(!$role->roleScore){
                    $role->createCompanyScoreInstance();
                }
                $role->setCompetitiveRoles();
            }

            $roleScores = $rolesByUserScore->map(function($role) use(&$total){
                $score = $role->user_engage_scores ? $role->user_engage_scores : 0;
                if($role->rolescore){
                    $role->roleScore->setAttribute("consolidated_value", $score);
                    return $role->roleScore;
                }

            });

            foreach($roleScores->values() as $key=>$score){
                $all = $roleScores->values();
                $versusId = null;
                $versusScore = null;
                if($all->count() < 2){
                    $this->info(sprintf('%1$s has less than 2 active roles.',
                        $company->name,
                    ));
                    // check if script execution stops here cuz of return
                    return;
                };
                if(isset($roleScores[$key+1])){
                    $versusId = $all[$key+1]->company_role_id;
                    $versusScore = $all[$key+1]->consolidated_value ? $all[$key+1]->consolidated_value : 0;

                } else {
                    $versusId = $all[$key-1]->company_role_id;
                    $versusScore = $all[$key-1]->consolidated_value ? $all[$key-1]->consolidated_value : 0;
                }
                $score->versus_period_start = Carbon::now()->endOfDay();
                $score->versus_period_end = Carbon::now()->endOfDay()->addDays(180);
                $score->versus_role_id  = $versusId;

                $this->info(sprintf('%1$s: (id:%2$d score:%3$d) against (id:%4$d score:%5$d)',
                    $company->name,
                    $score->company_role_id,
                    $score->consolidated_value,
                    $score->versus_role_id,
                    $versusScore
                ));
                $score->save();
            }
        }
    }
}
