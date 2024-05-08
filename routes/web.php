<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MeetController;
Route::get('/', [DashboardController::class, 'dashboard']);
Route::get('/meets', [MeetController::class, 'meets'])->name('meets');
