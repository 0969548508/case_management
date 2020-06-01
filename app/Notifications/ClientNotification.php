<?php

namespace App\Notifications;

use App\Models\Clients;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Auth;
use Log;

class ClientNotification extends Notification
{
    use Queueable;
    protected $client;
    protected $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Clients $client, $data)
    {
        $this->client = $client;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $content = '';

        switch ($this->data['slug']) {
            case 'client-create':
                $content = "<strong>" . ucwords($this->data['by']['name']) . " " . ucwords($this->data['by']['family_name']) . "</strong> has created a new client <strong>" . ucwords($this->data['info']['name']) . "</strong>";
                break;

            default:
                # code...
                break;
        }

        return [
            'content' => $content,
            'data'    => $this->data
        ];
    }
}
