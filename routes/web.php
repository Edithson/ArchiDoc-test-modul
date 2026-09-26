<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureSuperPrivileged;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [ArchiveController::class, 'index'])->name('archives.index');
    Route::get('/consultations', [ArchiveController::class, 'search'])->name('archives.search');
    Route::resource('archives', ArchiveController::class)->except(['index']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestion des Comptes Utilisateurs — Réservé aux Super Privilégiés
    Route::middleware([EnsureSuperPrivileged::class])->group(function () {
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::resource('users', UserController::class);
    });
});

require __DIR__.'/auth.php';
