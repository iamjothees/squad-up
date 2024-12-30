<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequirementResource\Pages;
use App\Filament\Resources\RequirementResource\RelationManagers;
use App\Models\Requirement;
use App\Service\RequirementService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequirementResource extends Resource
{
    protected static ?string $model = Requirement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(RequirementService::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns( RequirementService::getTableColumns() )
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
