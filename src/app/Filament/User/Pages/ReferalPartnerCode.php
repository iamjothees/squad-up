<?php

namespace App\Filament\User\Pages;

use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;

class ReferalPartnerCode extends Page
{
    protected static ?string $navigationIcon = 'icon-qr-code';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.user.pages.referal-partner-code';

    public User $user;

    public bool $verifiedUser = false;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record(auth()->user())
            ->schema([
                TextEntry::make('referal_partner_code')
                    ->hiddenLabel()
                    ->copyable()
                    ->weight(FontWeight::ExtraBold)
                    ->size(TextEntrySize::Large)
                    ->alignCenter()
                    ->icon('icon-clipboard')
                    ->iconPosition('after'),
            ]);
    }

    public function mount(){
        $this->user = auth()->user();
        $this->verifiedUser = !$this->user->is_verified;
    }
}
