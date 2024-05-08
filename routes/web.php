<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MeetController;
use App\Http\Controllers\BillController;
Route::get('/', [DashboardController::class, 'dashboard']);
Route::get('/meets', [MeetController::class, 'meets'])->name('meets');
Route::get('/bills', [BillController::class, 'bills'])->name('bills');
