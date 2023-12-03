<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class TaskAssigned extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $actionType;
    protected $name;
    public function __construct($name, $actionType)
    {
        $this->name = $name;
        $this->actionType = $actionType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }



    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
         if($this->actionType === 'post') {
        return [
            'data' =>' A new task has been assigned to you by'.' '.Auth::user()->name
        ];
      }

      else  {
        return [
            'data' =>' A task has been updated by'.' '.Auth::user()->name
        ];
      }

    //   return [
    //     'data' =>' A new task has been assigned to you by'.' '.Auth::user()->name
    // ];

    }
}
