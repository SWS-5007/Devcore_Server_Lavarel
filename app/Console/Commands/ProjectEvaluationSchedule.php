<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\ProjectStage;
use App\Services\ProjectService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectEvaluationSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:project:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init evaluations by schedule';

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
        $timeLimit = Carbon::now()->endOfDay()->addDays(-1);
        $this->info(sprintf('Processing project schedule [after: %s]...', $timeLimit->toISOString()));
        $count = 0;
       // $this->info($this->getProjects($timeLimit)->get());

        $this->getProjects($timeLimit)->chunk(50, function ($projects) use (&$count) {
           // $this->info(sprintf('Count: %s', $count));
            $count += 1;
            $this->processChunk($projects);
        });
        $this->info(sprintf('Processed %d scheduled projects', $count));
    }

        public function processChunk($projects)
    {
        $count = 0;
       $this->info(sprintf('PROCESS CHUNK: %d', $count ));
        return $projects->map(function ($project) use (&$count) {
            $project->stages->map(function ($stage) use (&$count, $project) {
                $this->info(sprintf('**** EVALUATING PROJECT *** [%s]...', $project->name));
                $this->info(sprintf('**** INITIALIZING STAGE EVALUATION FOR STAGE: *** [%s]...', $stage->id));

                Log::info(sprintf('**** EVALUATING PROJECT *** [%s]...', $project->name));
                if($stage->status === 'NOT_STARTED'){
                    return;
                } else {
                    Log::info(sprintf('**** INITIALIZING STAGE EVALUATION FOR STAGE: *** [%s]...', $stage->id));
                    ProjectService::instance()->initStageEvaluation($stage);
                    $count++;
                }
            });
            return $count;
        });
    }

    public function getProjects($timeLimit)
    {
        return Project::where('evaluation_type', 'PERIODIC')
            ->where('status', 'STARTED')
            ->with(['stages'])
            ->whereHas('stages', function ($builder) use ($timeLimit) {
                $builder->whereIn('status', ['STARTED', 'EVALUATIONS_PENDING'])
                    ->where(function ($q) use ($timeLimit) {
                        $this->info($timeLimit);
                        $q->where('next_schedule_process', '<=', $timeLimit)->orWhereNull('next_schedule_process');
                    });
            });


    }
}
