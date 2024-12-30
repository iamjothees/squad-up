<?php

namespace App\Service;

use App\Enum\RequirementStatus;
use App\Models\Project;
use App\Models\Requirement;
use Filament\Forms;
use Filament\Resources\Components\Tab;
use Filament\Tables;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;

class RequirementService
{
    public function __construct()
    {
        //
    }

    public function accept(Requirement $requirement): Requirement{
        $requirement->status = RequirementStatus::IN_PROGRESS;
        $requirement->admin_id = auth()->id();
        $requirement->save();

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
        $requirement->save();

        // credit points

        return $project;
    }

    public function createPoints( Requirement $requirement ){
        app(PointService::class)->createPoints(requirement: $requirement);
    }

    public function updatePoints( Requirement $requirement ){
        app(PointService::class)->updatePoints(requirement: $requirement);
    }

    public function destroyPoints( Requirement $requirement ){
        app(PointService::class)->destroyPoints(requirement: $requirement);
    }

    public function creditPoints( Requirement $requirement ){
        app(PointService::class)->creditPoints(requirement: $requirement);
    }

    public static function getFormSchema(): array{
        return [
            Forms\Components\Select::make('owner_id')
                ->label('Owner')
                ->relationship('owner', 'name', fn (Builder $query) => $query->onlyUsers())
                ->required()
                ->preload(config('app.env') === 'local')
                ->searchable()
                ->columnStart(1)
                ->visible(auth()->user()->hasRole('admin')),
            Forms\Components\Select::make('service_id')
                ->label('Service')
                ->relationship('service', 'name', fn (Builder $query) => $query->active())
                ->required()
                ->preload()
                ->searchable()
                ->default(1),
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
                ]),
            Forms\Components\Select::make('referer_id')
                ->label("Referer")
                ->relationship('referer', 'name', fn (Builder $query) => $query->whereNot('id', auth()->id()) )
                ->rules([
                    'not_in:'.auth()->id()
                ])
                ->searchable()
                ->visible(auth()->user()->hasRole('admin')),
            Forms\Components\Select::make('admin_id')
                ->label("Known Team member")
                ->relationship('admin', 'name', fn (Builder $query) => $query->onlyAdmins() )
                ->preload()
                ->searchable(),
            Forms\Components\TextInput::make('expecting_budget')
                ->required()
                ->hint(fn ($state) => Number::currency($state ?? 0) )
                ->prefix('₹')
                ->numeric()
                ->live(onBlur: true)
                ->minValue(0),
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
                ->visible(auth()->user()->hasRole('admin')),
            Tables\Columns\TextColumn::make('admin.name')
                ->label("Known Team member"),
            Tables\Columns\TextColumn::make('referer.name'),
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
            'yet_to_confirm' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', RequirementStatus::PENDING)),
            'in_progress' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', RequirementStatus::IN_PROGRESS)),
            'rejected' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', RequirementStatus::REJECTED)),
        ];
    }
}
