<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InvitationReceivedNotification extends Notification
{
    use Queueable;

    protected $invitation;
    protected $acceptUrl;

    public function __construct($invitation, $acceptUrl = null)
    {
        $this->invitation = $invitation;
        $this->acceptUrl = $acceptUrl;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $url = $this->acceptUrl ?? url(route('trips.show', $this->invitation->trip_id));
        return (new MailMessage)
                    ->subject('Kamu diundang bergabung ke Trip')
                    ->line('Kamu menerima undangan untuk bergabung ke trip.')
                    ->action('Lihat Trip', $url)
                ->line('Kunjungi halaman trip untuk menerima undangan.');
    }

    public function toArray($notifiable)
    {
        return [
            'invitation_id' => $this->invitation->id,
            'trip_id' => $this->invitation->trip_id,
            'invited_by' => $this->invitation->invited_by,
            'accept_url' => $this->acceptUrl,
        ];
    }
}
