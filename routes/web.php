<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MeetController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\LegislatorController;

Route::get('/', [DashboardController::class, 'dashboard']);
Route::get('/meets', [MeetController::class, 'meets'])->name('meets');
Route::get('/meet/{meet_id}', [MeetController::class, 'meet'])->name('meet');
Route::get('/bills', [BillController::class, 'bills'])->name('bills');
Route::get('/legislators', [LegislatorController::class, 'legislators'])->name('legislators');

use App\Http\Controllers\IVodController;
Route::get('/ivods/{date}/{meet_id}', [IVodController::class, 'ivods'])->name('ivods');
Route::get('/ivods/{date}', [IVodController::class, 'ivods'])->name('ivods');
Route::get('/ivods', [IVodController::class, 'ivods'])->name('ivods');
Route::get('/ivod/{ivod_id}', [IVodController::class, 'ivod'])->name('ivod');
