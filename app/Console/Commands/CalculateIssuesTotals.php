<?php

namespace App\Console\Commands;

use App\Models\Issue;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateIssuesTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:issue:calculatetotals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate the issues impact on projects';

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
        $this->info(sprintf('Processing issues totals...'));
        $count = 0;
        $this->getIssues()->chunk(50, function ($issues) use (&$count) {
            $count += $this->processChunk($issues);
        });
        $this->info(sprintf('Processed %d issues', $count));
    }

    public function processChunk($issues)
    {
        $count = 0;
        foreach ($issues as $issue) {
            $issue->calculateTotals();
            $issue->save();
            $issue->projectStage->calculateTotals();
            $issue->projectStage->save();
            $issue->project->calculateTotals();
            $issue->project->save();
            $count++;
        }
        return $count;
    }

    public function getIssues()
    {
        return Issue::with(['projectStage', 'project'])->whereHas('projectStage', function ($q) {
            $q->where('started_at', '!=', null)->where('closed_at', null);
        });
    }
}
