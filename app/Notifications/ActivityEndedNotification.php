<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ActivityEndedNotification extends Notification
{
    use Queueable;

    protected $activity;

    public function __construct($activity)
    {
        $this->activity = $activity;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Kegiatan Selesai: ' . $this->activity->title)
                    ->line('Kegiatan "' . $this->activity->title . '" telah selesai.')
                    ->action('Lihat Trip', url(route('trips.show', $this->activity->day->trip)))
                    ->line('Semoga menyenangkan!');
    }

    public function toArray($notifiable)
    {
        return [
            'activity_id' => $this->activity->id,
            'title' => $this->activity->title,
            'type' => 'ended',
        ];
    }
}
