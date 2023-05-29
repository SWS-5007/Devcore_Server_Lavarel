<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class UserCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $data;
    private $user;
    private $author;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data, $user, $author)
    {
        $this->data = $data;
        $this->user = $user;
        $this->author = $author;
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

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url= url(config('app.mobile_url'))."#/home?email=".$this->data['email']."&token=".$this->data['password'];
        if( $this->author->company != null ) {
            return (new MailMessage)
                ->subject('🚀' . Lang::get('messages.welcome', ['appName' => config('app.name')]))
                ->greeting(Lang::get('messages.hello', ['name' => $notifiable->first_name]))
                ->line(Lang::get('messages.hasInvited', ['name' => $this->author->first_name, 'companyName' => ($this->author->company)?$this->author->company->name:'test', 'appName' => config('app.name')]))
                ->line(Lang::get('messages.useCredentials'))
                ->line(Lang::get('messages.email') .': <b>' . $this->data['email'] . '</b>')
                ->line(Lang::get('messages.password') .': <b>' . $this->data['password'] . '</b>')
                ->action(Lang::get('messages.goToApp'), $url)
                ->line(Lang::get('messages.changePassword'));
        } else {
            return (new MailMessage)
            ->subject('🚀' . Lang::get('messages.welcome', ['appName' => config('app.name')]))
            ->greeting(Lang::get('messages.hello', ['name' => $notifiable->first_name]))
            ->line(Lang::get('messages.hasInvitedToNew', ['name' => $this->author->first_name, 'appName' => config('app.name')]))
            ->line(Lang::get('messages.useCredentials'))
            ->line(Lang::get('messages.email') .': <b>' . $this->data['email'] . '</b>')
            ->line(Lang::get('messages.password') .': <b>' . $this->data['password'] . '</b>')
            ->action(Lang::get('messages.goToApp'), $url)
            ->line(Lang::get('messages.changePassword'));
        }
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
