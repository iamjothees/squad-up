<?php

namespace App\Filament\Resources\RequirementResource\Pages;

use App\DTOs\RequirementDTO;
use App\Enums\RequirementStatus;
use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\RequirementResource;
use App\Models\Requirement;
use App\Services\RequirementService;
use Closure;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Number;

class ViewRequirement extends ViewRecord
{
    protected static string $resource = RequirementResource::class;
    private Closure $handleAccept;
    private Closure $handleReject;
    private Closure $createProject;

    public function __construct()
    {
        $this->handleAccept = function ($record, RequirementService $requirementService) {
            $requirementService->accept( requirementDTO: RequirementDTO::fromModel($record) );
            Notification::make('accepted')->title('Accepted')->warning()->send();
        };

        $this->handleReject = function ($record, RequirementService $requirementService) {
            $requirementService->reject(  requirementDTO: RequirementDTO::fromModel($record) );
            Notification::make('rejected')->title('Rejected')->warning()->send();
        };

        $this->createProject = function ($record, RequirementService $requirementService, array $data) {
            $requirementService->createProject( requirementDTO: RequirementDTO::fromModel($record), initialPayment: $data['initial_payment'] );
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
                ->form([
                    TextInput::make('initial_payment')
                        ->prefix('₹')
                        ->numeric()
                        ->hint((fn (Requirement $record) => 'Minimum amount: ' .  Number::currency($record->budget / 2)))
                        ->default(fn (Requirement $record) => $record->budget)
                        ->minValue(fn (Requirement $record) => $record->budget / 2)

                ])
                ->modalWidth('sm')
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
