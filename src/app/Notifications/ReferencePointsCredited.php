<?php

namespace App\Notifications;

use App\DTOs\ReferenceDTO;
use App\DTOs\RequirementDTO;
use App\Interfaces\PointGeneratorDTO;
use Filament\Notifications\Notification as NotificationsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReferencePointsCredited extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct( private ReferenceDTO $referenceDTO )
    {
        //
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
    public function toDatabase(object $notifiable): array
    {
        $reference = $this->referenceDTO->toModel();
        return NotificationsNotification::make()
            ->title("Points Credited for #{$reference->referenceable->toDTO()->getReferenceCode()}")
            ->getDatabaseMessage();
    }
}
