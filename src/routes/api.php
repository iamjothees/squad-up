<?php

use App\Http\Controllers\EnquiryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/enquiry', [EnquiryController::class, 'store']);
