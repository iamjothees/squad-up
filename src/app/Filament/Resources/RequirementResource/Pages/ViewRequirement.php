<?php

namespace App\Filament\Resources\RequirementResource\Pages;

use App\Enums\RequirementStatus;
use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\RequirementResource;
use App\Models\Requirement;
use App\Services\RequirementService;
use Closure;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewRequirement extends ViewRecord
{
    protected static string $resource = RequirementResource::class;
    private Closure $handleAccept;
    private Closure $handleReject;
    private Closure $createProject;

    public function __construct()
    {
        $this->handleAccept = function ($record, RequirementService $requirementService) {
            $requirementService->accept( requirement: $record);
            Notification::make('accepted')->title('Accepted')->warning()->send();
        };

        $this->handleReject = function ($record, RequirementService $requirementService) {
            $requirementService->reject( requirement: $record);
            Notification::make('rejected')->title('Rejected')->warning()->send();
        };

        $this->createProject = function ($record, RequirementService $requirementService) {
            $requirementService->createProject( requirement: $record);
            $record->refresh();
            Notification::make('project-created')->title('Project Created')->success()->send();
        };
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view-project')
                ->url(fn ($record) => ProjectResource::getUrl('view', ['record' => $record->project_id]))
                ->color('primary')
                ->visible(fn (Requirement $record) => $record->project),
            Actions\Action::make('create-project') // TODO: confirm project budget
                ->action($this->createProject)
                ->color('success')
                ->outlined()
                ->authorize(fn ($record) => auth()->user()->can('create-project', $record)),
            Actions\Action::make('reject')
                ->action($this->handleReject)
                ->color('danger')
                ->outlined()
                ->authorize(fn ($record) => auth()->user()->can('reject-requirement', $record)),
            Actions\Action::make('accept')
                ->action($this->handleAccept)
                ->label('Accept')
                ->color('warning')
                ->outlined()
                ->authorize(fn ($record) => auth()->user()->can('accept-requirement', $record)),
            Actions\EditAction::make()
                ->visible(fn (Requirement $record) => $record->status === RequirementStatus::PENDING),
        ];
    }
}
