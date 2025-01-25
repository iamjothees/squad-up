<?php

namespace App\Filament\Resources;

use App\Enums\EnquiryStatus;
use App\Filament\Resources\EnquiryResource\Pages;
use App\Filament\Resources\EnquiryResource\RelationManagers;
use App\Models\Enquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnquiryResource extends Resource
{
    protected static ?string $model = Enquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('enquirer_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('enquirer_contact')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options(EnquiryStatus::class)
                    ->default(EnquiryStatus::PENDING),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('enquirer_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('enquirer_contact')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->formatStateUsing(fn (EnquiryStatus $state): string => $state->getLabel()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Enquired On')
                    ->dateTime('d M, Y h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('move-to')
                    ->visible(fn (Enquiry $record) => $record->status === EnquiryStatus::PENDING)
                    ->form([
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options(EnquiryStatus::class)
                            ->default(EnquiryStatus::RESPONDED),
                    ])
                    ->action(function (Enquiry $record, array $data) {
                        $record->update(['status' => $data['status']]);
                        Notification::make('move-to-success')->title("Moved to {$data['status']->value}")->success()->send();
                    })
                    ->modalWidth('sm')
                    ->color('success'),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ManageEnquiries::route('/'),
        ];
    }
}
