<?php

namespace App\Console\Commands;

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

class NewIdeasCreated extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ideas:createdideas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get new ideas created in period';

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
        $timeLimit = Carbon::now()->endOfDay()->addDays(-8);
        $this->info(sprintf('Processing all created ideas [after: %s]...', $timeLimit->toISOString()));
        $count = 0;
        $this->getCompanyAdmins()->chunk(50, function ($admins) use (&$count,$timeLimit) {
            $count += $this->processChunk($admins,$timeLimit);
        });
        $this->info($count);
    }

    public function processChunk($admins,$timeLimit)
    {
        $count = 0;
        $this->info(sprintf('Processing chunk: '));
        $recipientsCount = $admins->map(function ($admin) use (&$count,$timeLimit) {

            $data = [];
            $companyRecipient = UserService::instance()->find()->where('id', $admin->model_id)->where('notifications', true)->whereNotNull("company_id");
            $companyRecipientId = $companyRecipient->pluck("company_id");
            if($companyRecipient->count() > 0){
                $processes = Process::where('company_id', $companyRecipientId)->with(["ideas"])->get();
                $users = UserService::instance()->find()->where('company_id', $companyRecipientId)->whereNotNull('company_id');

                    $data[] = $processes->map(function ($process) use ($timeLimit, $users) {
                    $processData = (object)['process_title' => $process->title];
                    // Idea issues
                        $ideaIssues = IdeaIssue::where(['process_id' => $process->id, 'company_id' =>$process->company_id ]);

                        foreach ($ideaIssues->get() as $ideaIssue) {
                            $ideaIssueProcessPath = [];
                            if($ideaIssue->parent_type == 'process_phase'){
                                $ideaIssueProcessPathFirst = ProcessPhase::where('id', $ideaIssue->parent_id)->first();
                                $ideaIssueProcessPathSecond = ProcessOperation::where('id', $ideaIssueProcessPathFirst->operation_id)->first();
                                $ideaIssueProcessPathThird = ProcessStage::where('id', $ideaIssueProcessPathSecond->stage_id)->first();
                                $ideaIssueProcessPath[] = $ideaIssueProcessPathFirst->title;
                                $ideaIssueProcessPath[] = $ideaIssueProcessPathSecond->title;
                                $ideaIssueProcessPath[] = $ideaIssueProcessPathThird->title;
                            } elseif($ideaIssue->parent_type == 'process_operation'){
                                $ideaIssueProcessPathFirst = ProcessOperation::where('id', $ideaIssue->parent_id)->first();
                                $ideaIssueProcessPathSecond = ProcessStage::where('id',$ideaIssueProcessPathFirst->stage_id)->first();
                                $ideaIssueProcessPath[] = $ideaIssueProcessPathFirst->title;
                                $ideaIssueProcessPath[] = $ideaIssueProcessPathSecond->title;
                            } elseif($ideaIssue->parent_type == 'process_stage') {
                                $ideaIssueProcessPathFirst = ProcessStage::where('id', $ideaIssue->parent_id)->first();
                                $ideaIssueProcessPath[] = $ideaIssueProcessPathFirst->title;
                            }

                            if($ideaIssue->created_at > $timeLimit){

                                $processIdeaIssue = (object) [
                                    'ideaIssue_desc' => $ideaIssue->description,
                                    'ideaIssue_type' => $ideaIssue->type,
                                    'ideaIssue_path' => $ideaIssueProcessPath,
                                    'ideaIssue_idea_title' => $process->ideas->where('id',$ideaIssue->idea_id)->first()->title,
                                    'ideaIssue_idea_desc' => $process->ideas->where('id',$ideaIssue->idea_id)->first()->description,
                                ];
                                if($ideaIssue->created_at > $timeLimit){

                                    $processData->processIdeaIssues[] = $processIdeaIssue;
                                }
                            }
                        }

                        //Issues
                    foreach ($process->issues as $issue) {
                        $issueProcessPath = [];
                        if($issue->parent_type == 'process_phase'){
                            $issueProcessPathFirst = ProcessPhase::where('id', $issue->parent_id)->first();
                            $issueProcessPathSecond = ProcessOperation::where('id', $issueProcessPathFirst->operation_id)->first();
                            $issueProcessPathThird = ProcessStage::where('id', $issueProcessPathSecond->stage_id)->first();
                            $issueProcessPath[] = $issueProcessPathFirst->title;
                            $issueProcessPath[] = $issueProcessPathSecond->title;
                            $issueProcessPath[] = $issueProcessPathThird->title;
                        } elseif($issue->parent_type == 'process_operation'){
                            $issueProcessPathFirst = ProcessOperation::where('id', $issue->parent_id)->first();
                            $issueProcessPathSecond = ProcessStage::where('id', $issueProcessPathFirst->stage_id)->first();
                            $issueProcessPath[] = $issueProcessPathFirst->title;
                            $issueProcessPath[] = $issueProcessPathSecond->title;
                        } elseif($issue->parent_type == 'process_stage') {
                            $issueProcessPathFirst = ProcessStage::where('id', $issue->parent_id)->first();
                            $issueProcessPath[] = $issueProcessPathFirst->title;
                        }
                            $processIssue = (object) [
                                'issue_desc' => $issue->description,
                                'issue_path' => $issueProcessPath
                            ];
                            if($issue->created_at > $timeLimit){
                                $processData->processIssues[] = $processIssue;
                        }
                    }

                    //Ideas
                    foreach ($process->ideas as $idea) {
                        $this->info($idea);
                        $ideaProcessPath = [];
                        if($idea->parent_type == 'process_phase'){
                            $ideaProcessPathFirst = ProcessPhase::where('id', $idea->parent_id)->first();
                            $ideaProcessPathSecond = ProcessOperation::where('id', $ideaProcessPathFirst->operation_id)->first();
                            $ideaProcessPathThird = ProcessStage::where('id', $ideaProcessPathSecond->stage_id)->first();
                            $ideaProcessPath[] = $ideaProcessPathFirst->title;
                            $ideaProcessPath[] = $ideaProcessPathSecond->title;
                            $ideaProcessPath[] = $ideaProcessPathThird->title;
                        } elseif($idea->parent_type == 'process_operation'){
                            $ideaProcessPathFirst = ProcessOperation::where('id', $idea->parent_id)->first();
                            $ideaProcessPathSecond = ProcessStage::where('id', $ideaProcessPathFirst->stage_id)->first();
                            $ideaProcessPath[] = $ideaProcessPathFirst->title;
                            $ideaProcessPath[] = $ideaProcessPathSecond->title;
                        } elseif($idea->parent_type == 'process_stage') {
                            $ideaProcessPathFirst = ProcessStage::where('id', $idea->parent_id)->first();
                            $ideaProcessPath[] = $ideaProcessPathFirst->title;
                        }
                        if($idea->created_at > $timeLimit){
                            $processIdea = (object) [
                                'idea_title' => $idea->title,
                                'idea_desc' => $idea->description,
                                'idea_created_at' => $idea->created_at,
                                'idea_source_id' => $idea->source_id,
                                'idea_tool' => $idea->company_tool_id,
                                'idea_path' => $ideaProcessPath,
                                'idea_created_at' => $idea->created_at,
                                'idea_process' => $process->title,
                            ];

                            if($idea->created_at > $timeLimit){
                                $processData->processIdeas[] = $processIdea;
                            }
                        }
                    }

                    return $processData;
                });

                $adminEmails = [];
                foreach ($companyRecipient->get() as $recipient) {
                    $adminEmails[] = $recipient->email;
                    $this->info(sprintf('Sending weekly newsletter to: %s...', $recipient->email));
                    $this->sendEmail($data,$timeLimit,$recipient);
                }

                return $adminEmails;

            }
            return $admin->email;
        });


        $this->info($recipientsCount);
        if(count($recipientsCount) > 0){
            return count($recipientsCount);
        } else {
            return 0;
        }
    }

    public function getCompanyAdmins(){
        $adminRoleId = [2,3];
        return ModelHasRole::whereIn('role_id', $adminRoleId);
    }

    public function sendEmail($data, $timeLimit, $recipient){
        $totalIdeas = $this->getStatCounts($data)->ideaCount;
        $totalIssues = $this->getStatCounts($data)->issueCount;
        $totalIdeaIssue = $this->getStatCounts($data)->ideaIssueCount;
        $this->subject = [
            'ideasCount' => $totalIdeas,
            'issuesCount' => $totalIssues,
            'ideaIssuesCount' => $totalIdeaIssue
        ];
        $this->recipient = $recipient;
        if($totalIdeas === 0 && $totalIssues === 0 && $totalIdeaIssue === 0) {
            return;
        }
        $beautyMail = app()->make( \Snowfire\Beautymail\Beautymail::class);
        $beautyMail->send('mail.weeklydigest', [
            'data' => $data,
            'user' => $recipient,
            'timeFrom' => $timeLimit->toDateString(),
            'timeTo' => \Illuminate\Support\Carbon::parse(Carbon::now())->format('Y-m-d'),
            'appUrl' => url(config('app.frontend_url')),
            'ideasCount' => $totalIdeas,
            'issuesCount' => $totalIssues,
            'ideaIssuesCount' => $totalIdeaIssue], function ($message) {
            return $message->to(
                $this->recipient->email,
                $this->recipient->first_name)
                ->subject(Lang::get('messages.hello',
                        ['name' => $this->recipient->first_name]) . Lang::get('messages.weeklyDigestTitle', $this->subject
                    ));
        });
    }
    public function getStatCounts($data) {
        $dataCounts = (object)[
            'ideaCount' => 0,
            'issueCount' => 0,
            'ideaIssueCount' => 0
        ];
        $ideaCounts[] = $data[0]->map(function ($process)  {
            if(property_exists($process, 'processIdeas')) {
                return count($process->processIdeas);
            } return 0;
        });
        $dataCounts->ideaCount = $ideaCounts[0]->sum();

        $issueCounts[] = $data[0]->map(function ($process)  {
            if(property_exists($process, 'processIssues')) {
                return count($process->processIssues);
            } return 0;
        });
        $dataCounts->issueCount = $issueCounts[0]->sum();

        $ideaIssueCount[] = $data[0]->map(function ($process)  {
            if(property_exists($process, 'processIdeaIssues')) {
                return count($process->processIdeaIssues);
            } return 0;
        });
        $dataCounts->ideaIssueCount = $ideaIssueCount[0]->sum();

        return $dataCounts;
    }

}
