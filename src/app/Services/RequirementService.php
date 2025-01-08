<?php

namespace App\Services;

use App\DTOs\ReferenceDTO;
use App\DTOs\RequirementDTO;
use App\Enums\RequirementStatus;
use App\Models\Project;
use App\Models\Requirement;
use App\Models\User;
use App\Settings\GeneralSettings;

//
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Resources\Components\Tab;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
//
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;
use Nette\Utils\Html;

class RequirementService
{
    public function __construct(  private PointService $pointService, private GeneralSettings $generalSettings, private ReferenceService $referenceService )
    {
        //
    }

    public function createRequirement(RequirementDTO $requirementDTO): RequirementDTO{

        return DB::transaction(function () use ($requirementDTO){
            // creates requirement
            $requirementDTO->referal_code = str()->uuid();
            $requirement = Requirement::create( $requirementDTO->toCreateArray() );
            
            // generates referal code
            $requirementDTO->id = $requirement->id;
            $requirement->referal_code = app(RequirementService::class)->generateReferalCode(requirementDTO: $requirementDTO);
            $requirement->save();
    
            // creates reference
            if ($requirementDTO->referer_id){
                $referenceDTO = ReferenceDTO::fromArray([ 'referenceable' => $requirement, 'referer_id' => $requirementDTO->referer_id, ]);
                $this->referenceService->createReference(referenceDTO: $referenceDTO, hasPoints: true);
            }
            return $requirementDTO;
        });
    }

    public function updateRequirement(RequirementDTO $requirementDTO): RequirementDTO{
        return DB::transaction(function () use ($requirementDTO){
            // updates requirement
            $requirement = $requirementDTO->toModel();
            $requirement->fill($requirementDTO->toUpdateArray());
            $requirement->push();
    
            if ($requirement->reference){
                $this->referenceService->destroyReference(referenceDTO: ReferenceDTO::fromModel($requirement->reference));
            }
    
            if ($requirementDTO->referer_id){
                $referenceDTO = ReferenceDTO::fromArray([ 'referenceable' => $requirement, 'referer_id' => $requirementDTO->referer_id, ]);
                $this->referenceService->createReference(referenceDTO: $referenceDTO, hasPoints: true);
            }
            $requirementDTO->refreshModel();
            return $requirementDTO;
        });
    }

    public function generateReferalCode(RequirementDTO $requirementDTO): string{
        return "REQ-".str($requirementDTO->id)->padLeft(4, '0')."-" . str(str()->uuid())->take(4)->upper();
    }

    public function accept(Requirement $requirement): Requirement{
        if ($requirement->status !== RequirementStatus::PENDING) throw new \Exception("Can't accept processed Requirement");

        $requirement->status = RequirementStatus::IN_PROGRESS;
        $requirement->admin_id = auth()->id();
        $requirement->save();

        return $requirement;    
    }

    public function reject(Requirement $requirement): Requirement{
        if ($requirement->status !== RequirementStatus::PENDING) throw new \Exception("Can't reject processed Requirement");

        $requirement->status = RequirementStatus::REJECTED;
        $requirement->save();

        // app(PointService::class)->destroyPoints(requirement: $requirement);

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

    private function handleReference(Requirement $requirement, RequirementDTO $requirementDTO){
        if ($requirement->reference){
            $requirement->reference->delete();
        }

        if ($requirementDTO->referer_id){
            $referenceDTO = ReferenceDTO::fromArray([
                'referenceable' => $requirement,
                'referer_id' => $requirementDTO->referer_id,
            ]);
            app(ReferenceService::class)->createReference(referenceDTO: $referenceDTO, hasPoints: true);
        }
    }


    // For Filament
    public static function getEloquentQuery(Builder $query): Builder{
        return $query
            ->whereNot('status', RequirementStatus::APPROVED)
            ->when(
                Filament::getCurrentPanel()->getId() === 'admin',
                fn (Builder $query) => $query->where(
                    fn ($q) =>  !auth()->user()->hasRole('admin') 
                                    ? $q->where('admin_id', auth()->id())->orWhereNull('admin_id')
                                    : $q
                ),
                fn (Builder $query) => $query->where(
                    fn ($q) => $q->where('owner_id', auth()->id())
                                ->orWhereHas(  'reference', fn ($q) => $q->where('referer_id', auth()->id()) )
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
                ->visible(fn ($state) => auth()->id() != $state),
            Forms\Components\Select::make('service_id')
                ->label('Service')
                ->relationship('service', 'name', fn (Builder $query) => $query->active())
                ->required()
                ->preload()
                ->searchable()
                ->default(1)
                ->columnStart(1),
            Forms\Components\TextInput::make('budget')
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
                ->visible(Filament::getCurrentPanel()->getId() !== 'admin'),
            Forms\Components\Select::make('referer_id') // TODO: handle reference creation
                ->label("Referer")
                ->options(User::pluck('name', 'id'))
                ->rules([
                    'not_in:'.auth()->id()
                ])
                ->validationMessages([
                    'not_in' => 'You cannot refer yourself'
                ])
                ->searchable()
                ->visible( fn () => Filament::getCurrentPanel()->getId() === 'admin'),
            Forms\Components\Select::make('admin_id')
                ->label("Known Team member")
                ->relationship('admin', 'name', fn (Builder $query) => $query->onlyTeamMembers() )
                ->preload()
                ->searchable(),
            Forms\Components\DateTimePicker::make('completion_at')
                ->seconds(false)
                ->label('Expected Delivery Date Time'),
        ];
    }

    public static function getTableColumns(): array{
        return [
            Tables\Columns\TextColumn::make('title')
                ->description(fn ($record) => $record->service->name)
                ->searchable(),
            Tables\Columns\TextColumn::make('owner.name')
                ->label("Owner")
                ->description(fn ($record) => Filament::getPanel() === 'admin' ? ($record->owner?->phone ?? $record->owner?->email) : '')
                ->visible(fn ($record) => auth()->user() != $record?->owner),
            Tables\Columns\TextColumn::make('admin.name')
                ->label("Known Team member"),
            Tables\Columns\TextColumn::make('reference.referer.name')
                ->description(fn ($record) => Filament::getPanel() === 'admin' ? ($record->referer?->phone ?? $record->referer?->email) : '')
                ->hidden(fn ($record) => $record?->reference?->referer === auth()->user()),
            Tables\Columns\TextColumn::make('expecting_delivery_at')
                ->label('Expected Delivery Date Time')
                ->dateTime('d-m-Y h:i A')
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('budget')
                ->label("Expecting Budget")
                ->money('INR')
                ->alignEnd()
                ->sortable(),
            Tables\Columns\TextColumn::make('reference.pointGeneration.points')
                ->label("Your share")
                ->formatStateUsing(
                    // fn ($state) => "<x-points points='{$state}' />"
                    fn ($state) => $state
                )
                ->alignEnd()
                ->sortable()
                ->html()
                ->visible(
                    fn () => Filament::getCurrentPanel()->getId() === 'user' && Route::currentRouteName() === 'filament.user.resources.requirements.refered'
                ),
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

    public function mutateFormDataBeforeFill(array $data, Requirement $requirement): array{
        $data['referal_partner_code'] ??= User::find($requirement->referer_id, ['referal_partner_code'])?->referal_partner_code;
        return $data;
    }
}
