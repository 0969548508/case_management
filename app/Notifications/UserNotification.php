<?php

namespace App\Notifications;

use App\User;
use App\Repositories\Users\UserRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Auth;
use Log;

class UserNotification extends Notification
{
    use Queueable;
    protected $user;
    protected $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $data)
    {
        $this->user = $user;
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
        $url = '';

        // action taken by user
        $this->data['by'] = [
            'id'           => auth()->user()->id,
            'name'         => auth()->user()->name,
            'family_name'  => auth()->user()->family_name,
            'image'        => UserRepository::loadImageUserLogin()
        ];

        switch ($this->data['slug']) {
            case 'user-assign-role':
                $url = 'showDetailUser';

                $content = "<strong>" . ucwords($this->data['by']['name']) . " " . ucwords($this->data['by']['family_name']) . "</strong> has changed the role of <strong>" . ucwords($this->data['info']['name']) . " " . ucwords($this->data['info']['family_name']) . "</strong> to <strong>" . ucwords(implode(", ", $this->data['info']['roleName'])) . "</strong>";
                break;

            case 'user-create':
                $url = 'showDetailUser';

                $content = "<strong>" . ucwords($this->data['by']['name']) . " " . ucwords($this->data['by']['family_name']) . "</strong> has created a new user <strong>" . ucwords($this->data['info']['name']) . " " . ucwords($this->data['info']['family_name']) . "</strong>";
                break;

            case 'user-update':
                $url = 'showDetailUser';

                $content = "<strong>" . ucwords($this->data['by']['name']) . " " . ucwords($this->data['by']['family_name']) . "</strong> has updated user <strong>" . ucwords($this->data['info']['name']) . " " . ucwords($this->data['info']['family_name']) . "</strong>";
                break;

            default:
                # code...
                break;
        }

        return [
            'content' => $content,
            'url'     => $url,
            'data'    => $this->data
        ];
    }
}
