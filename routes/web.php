<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ArchiveLocationController;
use App\Http\Controllers\ArchiveTypeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\PieceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [ArchiveController::class, 'index'])->name('archives.index');
    Route::get('/consultations', [ArchiveController::class, 'search'])->name('archives.search');
    Route::get('/archives/{archive}/download', [ArchiveController::class, 'download'])->name('archives.download');
    Route::resource('archives', ArchiveController::class)->except(['index']);
    Route::resource('archive-types', ArchiveTypeController::class);
    Route::resource('archive-locations', ArchiveLocationController::class);
    Route::resource('departments', DepartmentController::class);
    Route::get('personnels/{personnel}/download-zip', [PersonnelController::class, 'downloadZip'])->name('personnels.download-zip');
    Route::resource('personnels', PersonnelController::class);
    Route::resource('pieces', PieceController::class);

    // Historique des consultations & Boîte Noire (Scope filtré par rôle et département)
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
    Route::get('/activity-logs/archives-consultations', [ActivityLogController::class, 'archivesConsultations'])->name('activity-logs.archives-consultations');
    Route::get('/activity-logs/personnel-consultations', [ActivityLogController::class, 'personnelConsultations'])->name('activity-logs.personnel-consultations');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Administration Système & Habilitations (Contrôle d'accès fin géré au niveau contrôleur)
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/revoke-custom-permissions', [UserController::class, 'revokeCustomPermissions'])->name('users.revoke-custom-permissions');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    // Journaux réservés à la sécurité système & erreurs
    Route::get('/activity-logs/auth', [ActivityLogController::class, 'auth'])->name('activity-logs.auth');
    Route::get('/activity-logs/system', [ActivityLogController::class, 'system'])->name('activity-logs.system');

    // Paramètres système de l'application
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/reset', [SettingController::class, 'reset'])->name('settings.reset');

    Route::get('/activity-logs/{activity}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
});

require __DIR__.'/auth.php';
