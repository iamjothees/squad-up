<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\RequirementResource\Pages;
use App\Filament\User\Resources\RequirementResource\RelationManagers;
use App\Models\Requirement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;

class RequirementResource extends Resource
{
    protected static ?string $model = Requirement::class;

    protected static ?string $navigationIcon = 'icon-requirements';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Forms\Components\Select::make('referer_id')
                    ->label("Referer")
                    ->relationship('referer', 'name', fn (Builder $query) => $query->whereNot('id', auth()->id()) )
                    ->rules([
                        'not_in:'.auth()->id()
                    ])
                    ->searchable(),
                Forms\Components\Select::make('admin_id')
                    ->label("Known Team member")
                    ->relationship('admin', 'name', fn (Builder $query) => $query->onlyAdmin() )
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->description(fn ($record) => $record->service->name)
                    ->searchable(),
                Tables\Columns\TextColumn::make('owner.name')
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn (Requirement $record) => $record->canBeEditedBy(auth()->user())),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRequirements::route('/'),
            'create' => Pages\CreateRequirement::route('/create'),
            'view' => Pages\ViewRequirement::route('/{record}'),
            'edit' => Pages\EditRequirement::route('/{record}/edit'),
        ];
    }
}
