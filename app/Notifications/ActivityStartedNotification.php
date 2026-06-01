<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ActivityStartedNotification extends Notification
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
                    ->subject('Kegiatan Dimulai: ' . $this->activity->title)
                    ->line('Kegiatan "' . $this->activity->title . '" baru saja dimulai.')
                    ->action('Lihat Trip', url(route('trips.show', $this->activity->day->trip)))
                    ->line('Selamat menikmati perjalanan!');
    }

    public function toArray($notifiable)
    {
        return [
            'activity_id' => $this->activity->id,
            'title' => $this->activity->title,
            'type' => 'started',
        ];
    }
}
