<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppActivityNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $icon;
    protected $link;
    protected $type;

    public function __construct($message, $icon = '🔔', $link = '#', $type = 'generic')
    {
        $this->message = $message;
        $this->icon = $icon;
        $this->link = $link;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'icon'    => $this->icon,
            'link'    => $this->link,
            'type'    => $this->type,
        ];
    }
}
