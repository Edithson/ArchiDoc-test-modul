<?php

use App\Http\Controllers\ArchiveController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ArchiveController::class, 'index']);
Route::get('/consultations', [ArchiveController::class, 'search'])->name('archives.search');
Route::resource('archives', ArchiveController::class);
