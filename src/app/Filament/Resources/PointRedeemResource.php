<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PointRedeemResource\Pages;
use App\Filament\Resources\PointRedeemResource\RelationManagers;
use App\Models\PointRedeem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PointRedeemResource extends Resource
{
    protected static ?string $model = PointRedeem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('owner_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('points')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('redeemed_at'),
            ]);
    }

    public static function table(Table $table): Table
    {

        $redeemedAction = Tables\Actions\Action::make('mark-redeemed')
            ->label('Mark Redeemed')
            ->visible(fn (PointRedeem $record): bool => $record->redeemed_at == null)
            ->form([
                Forms\Components\DateTimePicker::make('redeemed_at')
                    ->required()
                    ->seconds(false)
                    ->default(now()),
            ])
            ->action(function (PointRedeem $record) {
                $record->update(['redeemed_at' => now()]);
            });

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('owner_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('points')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('redeemed_at')
                    ->dateTime('d-m-Y H:i A')
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
                $redeemedAction
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePointRedeems::route('/'),
        ];
    }
}
