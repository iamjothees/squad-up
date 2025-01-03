<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\RequirementResource\Pages;
use App\Filament\User\Resources\RequirementResource\RelationManagers;
use App\Models\Requirement;
use App\Service\RequirementService;
use Filament\Forms\Form;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RequirementResource extends Resource
{
    protected static ?string $model = Requirement::class;

    protected static ?string $navigationIcon = 'icon-requirements';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Requirements';
    protected static ?string $navigationLabel = 'Owned';

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make(static::getNavigationLabel())
                ->group(static::getNavigationGroup())
                ->parentItem(static::getNavigationParentItem())
                ->icon(static::getNavigationIcon())
                ->activeIcon(static::getActiveNavigationIcon())
                ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.*') && !request()->routeIs(static::getRouteBaseName() . '.refered'))
                ->badge(static::getNavigationBadge(), color: static::getNavigationBadgeColor())
                ->badgeTooltip(static::getNavigationBadgeTooltip())
                ->sort(static::getNavigationSort())
                ->url(static::getNavigationUrl()),
        ];
    }

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
            'refered' => Pages\ReferedRequirements::route('/refered'),
            'view' => Pages\ViewRequirement::route('/{record}'),
            'edit' => Pages\EditRequirement::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return RequirementService::getEloquentQuery( query: parent::getEloquentQuery() );
    }
}
