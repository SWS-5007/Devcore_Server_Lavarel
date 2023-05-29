<?php

namespace App\Console\Commands;

use App\Models\Idea;
use App\Models\IssueEffect;
use App\Models\ModelHasRole;
use App\Models\IdeaIssue;
use App\Models\Process;
use App\Models\ProcessOperation;
use App\Models\ProcessStage;
use App\Models\ProcessPhase;
use App\Models\Tool;
use App\Notifications\IdeasSummaryNotification;
use App\Services\UserService;
use App\Services\ProcessService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\View;

class SendWeeklyEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ideas:sendweeklyideas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send new ideas created in period';

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
        //$this->info(sprintf('Processing all created ideas [after: %s]...', $timeLimit->toISOString()));
        Log::info(sprintf('**** SENDING WEEKLY IDEAS *** [after: %s]...', $timeLimit->toISOString()));
        $count = 0;
        $this->getCompanyAdmins()->chunk(50, function ($admins) use (&$count, $timeLimit) {
            $count += $this->processChunk($admins, $timeLimit);
        });
    }
    public function processChunk($admins, $timeLimit)
    {
        $count = 0;
        $recipientsCount = $admins->map(function ($admin) use (&$count, $timeLimit) {
            $data = [];
            $companyRecipient = UserService::instance()->find()->where('id', $admin->model_id)->where('notifications', true)->whereNotNull("company_id");
            $companyRecipientId = $companyRecipient->pluck("company_id");
            $cr_ids = array();
            if (!empty($companyRecipientId) && count($companyRecipientId) > 0) {
                foreach ($companyRecipientId as $id) {
                    array_push($cr_ids, $id);
                }
            }
            $com_rec =  implode(",", $cr_ids);
            if ($companyRecipient->count() > 0) {
                // $processes = Process::where('company_id', $companyRecipientId)->with(["ideas"])->get();
                $query = "select  processes.id  from `processes`
                        left join `ideas` on `ideas`.`process_id` = `processes`.`id` AND (`ideas`.`created_at` > '" . $timeLimit . "' OR `ideas`.`updated_at` > '" . $timeLimit . "' )
                        left join `issues` on `issues`.`process_id` = `processes`.`id` AND (`issues`.`created_at` > '" . $timeLimit . "' OR `issues`.`updated_at` > '" . $timeLimit . "')
                        left join `idea_issues` on `idea_issues`.`process_id` = `processes`.`id` AND  (`idea_issues`.`created_at` > '" . $timeLimit . "' OR `idea_issues`.`updated_at` > '" . $timeLimit . "')
                        where (`processes`.`company_id` IN(" . $com_rec . ") AND (ideas.id is not Null OR issues.id is not Null or idea_issues.id is not Null) )GROUP BY processes.id";
                $processes_ids = DB::select($query);
                $ids = array();
                if (!empty($processes_ids)) {
                    foreach ($processes_ids as $process_id) {
                        array_push($ids, $process_id->id);
                    }
                }
                $processes = Process::whereIn('id', $ids)
                    ->with(["allIdeas"])->get();


                $users = UserService::instance()->find()->where([['company_id', '=', $companyRecipientId], ['notification', '=', 1]])->whereNotNull('company_id');


                $data[] = $processes->map(function ($process) use ($timeLimit, $users) {
                    $processData = (object)[
                        'process_title' => $process->title,
                        'process_id' => $process->id,
                    ];
                    // Idea issues
                    $ideaIssues = IdeaIssue::where(['process_id' => $process->id, 'company_id' => $process->company_id]);

                    foreach ($ideaIssues->get() as $ideaIssue) {
                        $ideaIssueProcessPath = [];
                        $ideaIssueUrl = $this->getIdeaUrl($ideaIssue->idea);

                        if ($ideaIssue->parent_type == 'process_phase') {
                            $ideaIssueProcessPathFirst = ProcessPhase::where('id', $ideaIssue->parent_id)->first();

                            if($ideaIssueProcessPathFirst && $ideaIssueProcessPathFirst->title){
                                $ideaIssueProcessPath[] = $ideaIssueProcessPathFirst->title;
                            }

                            if($ideaIssueProcessPathFirst && $ideaIssueProcessPathFirst->operation_id){
                                $ideaIssueProcessPathSecond = ProcessOperation::where('id', $ideaIssueProcessPathFirst->operation_id)->first();
                                if($ideaIssueProcessPathSecond && $ideaIssueProcessPathSecond->title){
                                    $ideaIssueProcessPath[] = $ideaIssueProcessPathSecond->title;
                                }
                                if($ideaIssueProcessPathSecond && $ideaIssueProcessPathSecond->stage_id){
                                    $ideaIssueProcessPathThird = ProcessStage::where('id', $ideaIssueProcessPathSecond->stage_id)->first();
                                    if($ideaIssueProcessPathThird && $ideaIssueProcessPathThird->title){
                                        $ideaIssueProcessPath[] = $ideaIssueProcessPathThird->title;

                                    }
                                }
                            }



                        } elseif ($ideaIssue->parent_type == 'process_operation') {
                            $ideaIssueProcessPathFirst = ProcessOperation::where('id', $ideaIssue->parent_id)->first();
                            if($ideaIssueProcessPathFirst && $ideaIssueProcessPathFirst->title){
                                $ideaIssueProcessPath[] = $ideaIssueProcessPathFirst->title;
                            }
                            if($ideaIssueProcessPathFirst && $ideaIssueProcessPathFirst->stage_id){
                                $ideaIssueProcessPathSecond = ProcessStage::where('id', $ideaIssueProcessPathFirst->stage_id)->first();
                                if($ideaIssueProcessPathSecond && $ideaIssueProcessPathSecond->title){
                                    $ideaIssueProcessPath[] = $ideaIssueProcessPathSecond->title;
                                }
                            }

                        } elseif ($ideaIssue->parent_type == 'process_stage') {
                            $ideaIssueProcessPathFirst = ProcessStage::where('id', $ideaIssue->parent_id)->first();
                            if($ideaIssueProcessPathFirst && $ideaIssueProcessPathFirst->title){
                                $ideaIssueProcessPath[] = $ideaIssueProcessPathFirst->title;
                            }
                        }

                     //   $this->info("Idea Issue Url:");
                      //  $this->info($ideaIssueUrl);


                        if ($ideaIssue->created_at > $timeLimit) {
                            $processIdeaIssue = (object) [
                                'ideaIssue_desc' => $ideaIssue->description,
                                'ideaIssue_type' => $ideaIssue->type,
                                'ideaIssue_path' => $ideaIssueProcessPath,
                                'ideaIssue_title' => $ideaIssue->title,
                                'ideaIssue_url' => $ideaIssueUrl,
                                'ideaIssue_idea_title' => $process->allIdeas->where('id', $ideaIssue->idea_id)->first()->title,
                                'ideaIssue_idea_desc' => $process->allIdeas->where('id', $ideaIssue->idea_id)->first()->description,
                                'created_at' => $ideaIssue->created_at,
                            ];
                            if ($ideaIssue->created_at > $timeLimit) {
                                $processData->processIdeaIssues[] = $processIdeaIssue;
                            }
                        }
                    }

                    //Issues
                    foreach ($process->issues as $issue) {
                        $issueProcessPath = [];
                        $issueUrl = url(config('app.url'))."/support/issues"."?processId=".$issue->process_id."&";

                        if ($issue->parent_type == 'process_phase') {
                            $issueProcessPathFirst = ProcessPhase::where('id', $issue->parent_id)->first();
                            if($issueProcessPathFirst && $issueProcessPathFirst->title){
                                $issueProcessPath[] = $issueProcessPathFirst->title;
                                $localPath = sprintf("%s=%s&", "phaseId", $issueProcessPathFirst->id);
                                $issueUrl .= $localPath;
                            }
                            if($issueProcessPathFirst && $issueProcessPathFirst->operation_id){
                                $issueProcessPathSecond = ProcessOperation::where('id', $issueProcessPathFirst->operation_id)->first();
                                if($issueProcessPathSecond && $issueProcessPathSecond->title){
                                    $issueProcessPath[] = $issueProcessPathSecond->title;
                                    $localPath = sprintf("%s=%s&", "operationId", $issueProcessPathSecond->id);
                                    $issueUrl .= $localPath;
                                }

                                if($issueProcessPathSecond && $issueProcessPathSecond->stage_id){
                                    $issueProcessPathThird = ProcessStage::where('id', $issueProcessPathSecond->stage_id)->first();
                                    if($issueProcessPathThird && $issueProcessPathThird->title){
                                        $issueProcessPath[] = $issueProcessPathThird->title;
                                        $localPath = sprintf("%s=%s&", "stageId", $issueProcessPathThird->id);
                                        $issueUrl .= $localPath;
                                    }
                                }
                            }

                        } elseif ($issue->parent_type == 'process_operation') {
                            $issueProcessPathFirst = ProcessOperation::where('id', $issue->parent_id)->first();
                            if($issueProcessPathFirst && $issueProcessPathFirst->title){
                                $issueProcessPath[] = $issueProcessPathFirst->title;
                                $localPath = sprintf("%s=%s&", "operationId", $issueProcessPathFirst->id);
                                $issueUrl .= $localPath;
                            }
                            if($issueProcessPathFirst && $issueProcessPathFirst->stage_id){
                                $issueProcessPathSecond = ProcessStage::where('id', $issueProcessPathFirst->stage_id)->first();
                                if($issueProcessPathSecond && $issueProcessPathSecond->title){
                                    $issueProcessPath[] = $issueProcessPathSecond->title;
                                    $localPath = sprintf("%s=%s&", "stageId", $issueProcessPathSecond->id);
                                    $issueUrl .= $localPath;
                                }
                            }

                        } elseif ($issue->parent_type == 'process_stage') {
                            $issueProcessPathFirst = ProcessStage::where('id', $issue->parent_id)->first();
                            if($issueProcessPathFirst && $issueProcessPathFirst->title){
                                $issueProcessPath[] = $issueProcessPathFirst->title;
                                $localPath = sprintf("%s=%s&", "stageId", $issueProcessPathFirst->id);
                                $issueUrl .= $localPath;
                            }

                        }
                        $issueTemplateName = null;
                        if($issue->effect_template_id){
                            $effect = IssueEffect::where("id", $issue->effect_template_id)->first();
                            if($effect && $effect->title){
                                $issueTemplateName = $effect->title;
                            }
                        }

                        $issueUrl .= "id=".$issue->id;

                        $processIssue = (object) [
                            'issue_desc' => $issue->description,
                            'issue_path' => $issueProcessPath,
                            'issue_url' => $issueUrl,
                            'created_at' => $issue->created_at,
                            'type' => $issue->parent_type,
                            'effect' => $issueTemplateName
                        ];

                        if ($issue->created_at > $timeLimit) {
                            $processData->processIssues[] = $processIssue;
                        }
                    }

                    //Ideas
                    foreach ($process->allIdeas as $idea) {


                        $ideaProcessPath = [];
                        $ideaStatus = "";
                        $section = "ideas";

                        if($idea->status == Idea::IDEA_STATUS[100]){
                            $ideaStatus .= "/review";
                        }

                        if($idea->status == Idea::IDEA_STATUS[300]){
                            $ideaStatus .= "/adopted";
                        }

//                        if($idea->company_tool_id){
//                            $section = "tool-ideas";
//                        }

                        $ideaUrl = url(config('app.url'))."/improve/".$section.$ideaStatus."?processId=".$idea->process_id."&";
                        if ($idea->parent_type == 'process_phase') {
                            $ideaProcessPathFirst = ProcessPhase::where('id', $idea->parent_id)->first();
                            if($ideaProcessPathFirst && $ideaProcessPathFirst->title){
                                $ideaProcessPath[] = $ideaProcessPathFirst->title;
                                $localPath = sprintf("%s=%s&", "phaseId", $ideaProcessPathFirst->id);
                                $ideaUrl .= $localPath;
                            }

                            if($ideaProcessPathFirst && $ideaProcessPathFirst->operation_id){
                                $ideaProcessPathSecond = ProcessOperation::where('id', $ideaProcessPathFirst->operation_id)->first();
                                if($ideaProcessPathSecond && $ideaProcessPathSecond->title){
                                    $ideaProcessPath[] = $ideaProcessPathSecond->title;
                                    $localPath = sprintf("%s=%s&", "operationId", $ideaProcessPathSecond->id);
                                    $ideaUrl .= $localPath;
                                }

                                if($ideaProcessPathSecond && $ideaProcessPathSecond->stage_id){
                                    $ideaProcessPathThird = ProcessStage::where('id', $ideaProcessPathSecond->stage_id)->first();
                                    if($ideaProcessPathThird && $ideaProcessPathThird->title){
                                        $ideaProcessPath[] = $ideaProcessPathThird->title;
                                        $localPath = sprintf("%s=%s&", "stageId", $ideaProcessPathThird->id);
                                        $ideaUrl .= $localPath;
                                    }
                                }
                            }

                        } elseif ($idea->parent_type == 'process_operation') {
                            $ideaProcessPathFirst = ProcessOperation::where('id', $idea->parent_id)->first();
                            if($ideaProcessPathFirst && $ideaProcessPathFirst->title){
                                $ideaProcessPath[] = $ideaProcessPathFirst->title;
                                $localPath = sprintf("%s=%s&", "operationId", $ideaProcessPathFirst->id);
                                $ideaUrl .= $localPath;
                            }

                            if($ideaProcessPathFirst && $ideaProcessPathFirst->stage_id){
                                $ideaProcessPathSecond = ProcessStage::where('id', $ideaProcessPathFirst->stage_id)->first();
                                if($ideaProcessPathSecond && $ideaProcessPathSecond->title){
                                    $ideaProcessPath[] = $ideaProcessPathSecond->title;
                                    $localPath = sprintf("%s=%s&", "stageId", $ideaProcessPathSecond->id);
                                    $ideaUrl .= $localPath;
                                }
                            }


                        } elseif ($idea->parent_type == 'process_stage') {
                            $ideaProcessPathFirst = ProcessStage::where('id', $idea->parent_id)->first();
                            if($ideaProcessPathFirst && $ideaProcessPathFirst->title){
                                $ideaProcessPath[] = $ideaProcessPathFirst->title;
                                $localPath = sprintf("%s=%s&", "stageId", $ideaProcessPathFirst->id);
                                $ideaUrl .= $localPath;
                            }
                        }

                        $ideaUrl .= "id=".$idea->id;

//                        $toolNames = [];
//                        if($idea->company_tool_ids){
//                            $toolNames[] = $idea->companyTools->map(function ($companyTool) {
//                                $this->info(json_encode($companyTool->tool->name));
//                                //$companyTool->getNameAttribute();
//                                return $companyTool->tool->name;
//                            });
//                        }


                        if ($idea->created_at > $timeLimit) {

                            $processIdea = (object) [
                                'idea_title' => $idea->title,
                                'idea_desc' => $idea->description,
                                'idea_source_id' => $idea->source_id,
//                                'idea_tools' => $toolNames,
                                'idea_path' => $ideaProcessPath,
                                'idea_url' => $ideaUrl,
                                'idea_created_at' => $idea->created_at,
                                'idea_process' => $process->title,
                            ];


                            if ($idea->created_at > $timeLimit) {
                                $processData->processIdeas[] = $processIdea;
                            }
                        }
                    }

                    return $processData;
                });

                $adminEmails = [];
                foreach ($companyRecipient->get() as $recipient) {
                        if ($recipient) {
                            $adminEmails[] = $recipient->email;
                            // Log::info($recipient->email);
                            Log::info(sprintf('Sending weekly newsletter to: %s...', $recipient->email));
                            // $this->info(sprintf('Sending weekly newsletter to: %s...', $recipient->email));
                            $this->sendEmail($data, $timeLimit, $recipient);
                        }
                }

                return $adminEmails;
            }
            return $admin->email;
        });


      //  $this->info($recipientsCount);
        if (count($recipientsCount) > 0) {
            return count($recipientsCount);
        } else {
            return 0;
        }
    }

    public function getCompanyAdmins()
    {
        $adminRoleId = [2, 3];
        return ModelHasRole::whereIn('role_id', $adminRoleId);
    }

    public function sendEmail($data, $timeLimit, $recipient)
    {


        $totalIdeas = $this->getStatCounts($data)->ideaCount;
        $totalIssues = $this->getStatCounts($data)->issueCount;
        $totalIdeaIssue = $this->getStatCounts($data)->ideaIssueCount;
        // $this->info(sprintf('idea stats: %s...', $totalIdeas));
        Log::info(sprintf('idea stats: %s...', $totalIdeas));
        // $this->info(sprintf('issue stats: %s...', $totalIssues));
        Log::info(sprintf('issue stats: %s...', $totalIssues));
        // $this->info(sprintf('idea issues stats: %s...', $totalIdeaIssue));
        Log::info(sprintf('idea issues stats: %s...', $totalIdeaIssue));
        $this->subject = [
            'ideasCount' => $totalIdeas,
            'issuesCount' => $totalIssues,
            'ideaIssuesCount' => $totalIdeaIssue
        ];
        $this->recipient = $recipient;
        if ($totalIdeas === 0 && $totalIssues === 0 && $totalIdeaIssue === 0) {
            return;
        }
        $beautyMail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $beautyMail->send('mail.weeklydigest', [
            'data' => $data,
            'user' => $recipient,
            'timeFrom' => $timeLimit->toDateString(),
            'timeTo' => \Illuminate\Support\Carbon::parse(Carbon::now())->format('Y-m-d'),
            'appUrl' => url(config('app.frontend_url')),
            'ideasCount' => $totalIdeas,
            'issuesCount' => $totalIssues,
            'ideaIssuesCount' => $totalIdeaIssue
        ], function ($message) {
            return $message->to(
                $this->recipient->email,
                $this->recipient->first_name
            )
                // ->from('abc@gmail.com')
                ->subject(Lang::get(
                    // 'messages.helo',
                        'messages.HelloName',
                        ['name' => $this->recipient->first_name]
                    ) . Lang::get(
                        'messages.weeklyDigestTitle',
                        $this->subject
                    ));
        });
    }
    public function getStatCounts($data)
    {
        $dataCounts = (object)[
            'ideaCount' => 0,
            'issueCount' => 0,
            'ideaIssueCount' => 0
        ];
        $ideaCounts[] = $data[0]->map(function ($process) {
            if (property_exists($process, 'processIdeas')) {
                return count($process->processIdeas);
            }
            return 0;
        });
        $dataCounts->ideaCount = $ideaCounts[0]->sum();

        $issueCounts[] = $data[0]->map(function ($process) {
            if (property_exists($process, 'processIssues')) {
                return count($process->processIssues);
            }
            return 0;
        });
        $dataCounts->issueCount = $issueCounts[0]->sum();

        $ideaIssueCount[] = $data[0]->map(function ($process) {
            if (property_exists($process, 'processIdeaIssues')) {
                return count($process->processIdeaIssues);
            }
            return 0;
        });
        $dataCounts->ideaIssueCount = $ideaIssueCount[0]->sum();

        return $dataCounts;
    }

    public function getIdeaUrl($idea){
        $ideaStatus = "";
        $section = "ideas";

        if($idea->status == Idea::IDEA_STATUS[100]){
            $ideaStatus .= "/review";
        }

        if($idea->status == Idea::IDEA_STATUS[300]){
            $ideaStatus .= "/adopted";
        }

        if($idea->company_tool_id){
            $section = "tool-ideas";
        }

        $ideaUrl = url(config('app.url'))."/improve/".$section.$ideaStatus."?processId=".$idea->process_id."&";
        if ($idea->parent_type == 'process_phase') {
            $ideaProcessPathFirst = ProcessPhase::where('id', $idea->parent_id)->first();
            if($ideaProcessPathFirst){
                $localPath = sprintf("%s=%s&", "phaseId", $ideaProcessPathFirst->id);
                $ideaUrl .= $localPath;
            }

            if($ideaProcessPathFirst && $ideaProcessPathFirst->operation_id){
                $ideaProcessPathSecond = ProcessOperation::where('id', $ideaProcessPathFirst->operation_id)->first();
                if($ideaProcessPathSecond){
                    $localPath = sprintf("%s=%s&", "operationId", $ideaProcessPathSecond->id);
                    $ideaUrl .= $localPath;
                }

                if($ideaProcessPathSecond && $ideaProcessPathSecond->stage_id){
                    $ideaProcessPathThird = ProcessStage::where('id', $ideaProcessPathSecond->stage_id)->first();
                    if($ideaProcessPathThird){
                        $localPath = sprintf("%s=%s&", "stageId", $ideaProcessPathThird->id);
                        $ideaUrl .= $localPath;
                    }
                }
            }

        } elseif ($idea->parent_type == 'process_operation') {
            $ideaProcessPathFirst = ProcessOperation::where('id', $idea->parent_id)->first();
            if($ideaProcessPathFirst && $ideaProcessPathFirst->title){
                $localPath = sprintf("%s=%s&", "operationId", $ideaProcessPathFirst->id);
                $ideaUrl .= $localPath;
            }

            if($ideaProcessPathFirst && $ideaProcessPathFirst->stage_id){
                $ideaProcessPathSecond = ProcessStage::where('id', $ideaProcessPathFirst->stage_id)->first();
                if($ideaProcessPathSecond){
                    $localPath = sprintf("%s=%s&", "stageId", $ideaProcessPathSecond->id);
                    $ideaUrl .= $localPath;
                }
            }


        } elseif ($idea->parent_type == 'process_stage') {
            $ideaProcessPathFirst = ProcessStage::where('id', $idea->parent_id)->first();
            if($ideaProcessPathFirst){
                $localPath = sprintf("%s=%s&", "stageId", $ideaProcessPathFirst->id);
                $ideaUrl .= $localPath;
            }
        }

        $ideaUrl .= "id=".$idea->id;
        $toolName = null;
        if($idea->company_tool_id){
            $ideaUrl .= "&toolId=".$idea->company_tool_id;
        }
        return $ideaUrl;
    }

}
