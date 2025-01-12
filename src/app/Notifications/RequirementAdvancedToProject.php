<?php

namespace App\Notifications;

use App\DTOs\ProjectDTO;
use App\DTOs\RequirementDTO;
use App\Models\Requirement;
use Filament\Notifications\Notification as NotificationsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequirementAdvancedToProject extends Notification
{
    use Queueable;

    public function __construct( private RequirementDTO $requirementDTO )
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toDatabase(object $notifiable): array
    {
        $projectDTO = ProjectDTO::fromModel($this->requirementDTO->toModel()->project);
        return NotificationsNotification::make()
            ->title("#{$this->requirementDTO->reference_code} Advanced To \n Project #{$projectDTO->reference_code}")
            ->getDatabaseMessage();
    }
}
