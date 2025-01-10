<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\ProjectResource\Pages;
use App\Filament\User\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'icon-projects';
    protected static ?int $navigationSort = 5;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('admin.name')
                    ->description(fn ($record) => $record->admin->phone)
                    ->searchable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime('d-m-Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('expected_completed_at')
                    ->dateTime('d-m-Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime('d-m-Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('delivered_at')
                    ->dateTime('d-m-Y h:i A')
                    ->visible(fn (?Project $record): bool => $record?->delivered_at !== null)
                    ->sortable(),
                Tables\Columns\TextColumn::make('committed_budget')
                    ->numeric()
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority_range')
                    ->numeric()
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
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProjects::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return Project::query()->where('owner_id', auth()->id());
    }
}
