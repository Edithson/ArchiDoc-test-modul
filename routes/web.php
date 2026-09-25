<?php

use App\Http\Controllers\ArchiveController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ArchiveController::class, 'index']);
Route::resource('archives', ArchiveController::class);
