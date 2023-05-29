<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Carbon;


class IdeasSummaryNotification extends Notification
{
    use Queueable;
    private $data;
    private $user;
    private $processesData;
    private $author;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data, $timeLimit, $user)
    {
        $this->data = $data;
        $this->timeLimit = $timeLimit;
        $this->user = $user;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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



    public function toMail($notifiable)
    {
        $totalIdeas = $this->getStatCounts($this->data)->ideaCount;
        $totalIssues = $this->getStatCounts($this->data)->issueCount;
        $totalIdeaIssue = $this->getStatCounts($this->data)->ideaIssueCount;
        $data = $this->data;
        var_dump(":::::::::::::::::::::::::::::::::::::::::::::::::");
        var_dump($data);
        $beautyMail = app()->make( \Snowfire\Beautymail\Beautymail::class);
            return $beautyMail->send('mail.weeklydigest', [
                'data' => $data,
                'user' => $this->user,
                'timeFrom' => $this->timeLimit->toDateString(),
                'timeTo' => Carbon::parse(Carbon::now())->format('Y-m-d'),
                'appUrl' => url(config('app.frontend_url')),
                'ideasCount' => $totalIdeas,
                'issuesCount' => $totalIssues,
                'ideaIssuesCount' => $totalIdeaIssue], function ($message) {
             return $message->to($this->user->email, $this->user->first_name)
                ->subject(Lang::get('messages.hello', ['name' => $this->user->first_name]) . Lang::get('messages.weeklyDigestTitle', ['ideasCount' => $this->getStatCounts($this->data)->ideaCount, 'issuesCount' => $this->getStatCounts($this->data)->issueCount, 'ideaIssuesCount' => $this->getStatCounts($this->data)->ideaIssueCount]));
        });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
