<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('filament.user.pages.dashboard'));
