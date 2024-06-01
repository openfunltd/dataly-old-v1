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
Route::get('/ivod/datelist', [IVodController::class, 'datelist'])->name('ivods.datelist');
Route::get('/ivod/datelist/{term}/{sessionPeriod}', [IVodController::class, 'datelist'])->name('ivods.datelist.term');
Route::get('/ivod/date/{date}/{meet_id}', [IVodController::class, 'ivods'])->name('ivods');
Route::get('/ivod/date/{date}', [IVodController::class, 'ivods'])->name('ivods.date');
Route::get('/ivod', [IVodController::class, 'ivods'])->name('ivods');
Route::get('/ivod/show/{ivod_id}', [IVodController::class, 'ivod'])->name('ivod');

use App\Http\Controllers\GazetteController;
Route::get('/gazette', [GazetteController::class, 'gazettes'])->name('gazettes');
Route::get('/gazette/{year}', [GazetteController::class, 'gazettes'])->name('gazettes.year');
