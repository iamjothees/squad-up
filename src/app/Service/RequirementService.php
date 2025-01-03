<?php

namespace App\Service;

use App\Enum\RequirementStatus;
use App\Models\Project;
use App\Models\Requirement;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Resources\Components\Tab;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;

class RequirementService
{
    public function __construct()
    {
        //
    }

    public function generateReferalCode(Requirement $requirement): string{
        return "REQ-".str($requirement->id)->padLeft(4, '0')."-" . str(str()->uuid())->take(4)->upper();
    }

    public function accept(Requirement $requirement): Requirement{
        if ($requirement->status !== RequirementStatus::PENDING) throw new \Exception("Can't accept processed Requirement");

        $requirement->status = RequirementStatus::IN_PROGRESS;
        $requirement->admin_id = auth()->id();
        $requirement->save();

        app(PointService::class)->updateOrCreatePoints(requirement: $requirement);

        return $requirement;    
    }

    public function reject(Requirement $requirement): Requirement{
        if ($requirement->status !== RequirementStatus::PENDING) throw new \Exception("Can't reject processed Requirement");

        $requirement->status = RequirementStatus::REJECTED;
        $requirement->save();

        app(PointService::class)->destroyPoints(requirement: $requirement);

        return $requirement;    
    }

    public function createProject(Requirement $requirement): Project{
        $project = Project::create([
            'title' => $requirement->title,
            'description' => $requirement->description,
            'service_id' => $requirement->service_id,
            'admin_id' => $requirement->admin_id,
            'committed_budget' => $requirement->expecting_budget,
        ]);
        $requirement->project_id = $project->id;
        $requirement->status = RequirementStatus::APPROVED;
        $requirement->save();

        // crediting points through observer

        // Notify owner

        return $project;
    }

    public function createPoints( Requirement $requirement ){
        app(PointService::class)->createPoints(requirement: $requirement);
    }

    public function updateOrCreatePoints( Requirement $requirement ){
        app(PointService::class)->updateOrCreatePoints(requirement: $requirement);
    }

    public function destroyPoints( Requirement $requirement ){
        app(PointService::class)->destroyPoints(requirement: $requirement);
    }

    public function creditPoints( Requirement $requirement ){
        app(PointService::class)->creditPoints(requirement: $requirement);
    }

    public static function getEloquentQuery(Builder $query): Builder{
        return $query
            ->whereNot('status', RequirementStatus::APPROVED)
            ->when(
                Filament::getCurrentPanel()->getId() === 'admin',
                fn (Builder $query) => $query->where(
                    fn ($q) => $q->where('admin_id', auth()->id())->orWhereNull('admin_id')
                ),
                fn (Builder $query) => $query->where(
                    fn ($q) => $q->where('owner_id', auth()->id())->orWhere('referer_id', auth()->id())
                )
            );
    }

    public static function getFormSchema(): array{
        return [
            Forms\Components\Select::make('owner_id')
                ->label('Owner')
                ->relationship('owner', 'name')
                ->required()
                ->preload(config('app.env') === 'local')
                ->searchable()
                ->visible(auth()->user()->hasRole('admin')),
            Forms\Components\Select::make('service_id')
                ->label('Service')
                ->relationship('service', 'name', fn (Builder $query) => $query->active())
                ->required()
                ->preload()
                ->searchable()
                ->default(1)
                ->columnStart(1),
            Forms\Components\TextInput::make('expecting_budget')
                ->required()
                ->hint(fn ($state) => Number::currency($state ?? 0) )
                ->prefix('₹')
                ->numeric()
                ->live(onBlur: true)
                ->minValue(0),
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
            Forms\Components\Textarea::make('description')
                ->columnSpanFull()
                ->rows(3),
            Forms\Components\TextInput::make('referal_partner_code')
                ->label("Referal Partner Code")
                ->rules([
                    Rule::exists('users', 'referal_partner_code')
                            ->where( fn ($q) => $q->whereNot('id', auth()->id()) )
                ])
                ->visible(!auth()->user()->hasRole('admin')),
            Forms\Components\Select::make('referer_id')
                ->label("Referer")
                ->relationship('referer', 'name' )
                ->rules([
                    'not_in:'.auth()->id()
                ])
                ->validationMessages([
                    'not_in' => 'You cannot refer yourself'
                ])
                ->searchable()
                ->visible(auth()->user()->hasRole('admin')),
            Forms\Components\Select::make('admin_id')
                ->label("Known Team member")
                ->relationship('admin', 'name', fn (Builder $query) => $query->onlyAdmins() )
                ->preload()
                ->searchable(),
            Forms\Components\DateTimePicker::make('required_at')->seconds(false),
        ];
    }

    public static function getTableColumns(): array{
        return [
            Tables\Columns\TextColumn::make('title')
                ->description(fn ($record) => $record->service->name)
                ->searchable(),
            Tables\Columns\TextColumn::make('owner.name')
                ->label("Owner")
                ->description(fn ($record) => auth()->user()->hasRole('admin') ? ($record->owner?->phone ?? $record->owner?->email) : '')
                ->visible(auth()->user()->hasRole('admin')),
            Tables\Columns\TextColumn::make('admin.name')
                ->label("Known Team member"),
            Tables\Columns\TextColumn::make('referer.name')
                ->description(fn ($record) => auth()->user()->hasRole('admin') ? ($record->referer?->phone ?? $record->referer?->email) : ''),
            Tables\Columns\TextColumn::make('required_at')
                ->dateTime('d-m-Y h:i A'),
            Tables\Columns\TextColumn::make('expecting_budget')
                ->money('INR')
                ->alignEnd()
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('deleted_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getTabs(): array{
        return [
            'queued_for_confirmation' => Tab::make()
                ->label('Queued for Confirmation')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', RequirementStatus::PENDING)),
            'active' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', RequirementStatus::IN_PROGRESS)),
            'rejected' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', RequirementStatus::REJECTED)),
        ];
    }
}
