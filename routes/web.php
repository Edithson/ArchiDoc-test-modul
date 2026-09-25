<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArchiveController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('archives', ArchiveController::class);
