<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Page;

class ShareApp extends Page
{
    protected static ?string $navigationIcon = 'icon-team';
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.user.pages.share-app';

    protected static ?string $title = 'Build the Squad';

    protected static ?string $slug = 'build-the-squad';

    public function getHeading(): string
    {
        return "";
    }


}
