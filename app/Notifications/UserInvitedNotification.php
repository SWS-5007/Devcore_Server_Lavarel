<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;

class UserInvitedNotification extends  Notification implements ShouldQueue
{
    use Queueable;
    private $project;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($project)
    {
        //
        $this->project = $project;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // return [FcmChannel::class];
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return FcmMessage
     */

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🔐' . Lang::get('Your password has changed'))
            ->greeting(Lang::get('Hello name!', ['name' => $notifiable->first_name]))
            ->line(Lang::get('We inform that we have generated a new password for you.'))
            ->action(Lang::get('Go to the app'), url(config('app.frontend_url')))
            ->line(Lang::get('You must change this password after your first login.'));

    }

    public function toFcm($notifiable)
    {

            $bodyMsg = "You are invited to ".$this->project->name;
            return FcmMessage::create()
            ->setData(['pid' => ''.$this->project->id,'etype'=>'2'])
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle('Invited to DevCore Project')
                ->setBody($bodyMsg)
                ->setImage('http://example.com/url-to-image-here.png'))
            ->setAndroid(
                AndroidConfig::create()
                    ->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('analytics'))
                    ->setNotification(AndroidNotification::create()->setColor('#0A0A0A'))

            );

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
