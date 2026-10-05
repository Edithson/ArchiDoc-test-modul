<?php

namespace App\Providers;

use App\Listeners\LogAuthEvents;
use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, [LogAuthEvents::class, 'handleLogin']);
        Event::listen(Failed::class, [LogAuthEvents::class, 'handleFailed']);
        Event::listen(Logout::class, [LogAuthEvents::class, 'handleLogout']);
        Event::listen(Lockout::class, [LogAuthEvents::class, 'handleLockout']);

        // Register dynamic Gates for the 9 key models and their supported actions
        $modelDefinitions = [
            'User' => ['read', 'create', 'update', 'delete'],
            'Archive' => ['read', 'create', 'update', 'delete', 'download'],
            'ArchiveLocation' => ['read', 'create', 'update', 'delete'],
            'ArchiveType' => ['read', 'create', 'update', 'delete'],
            'Department' => ['read', 'create', 'update', 'delete'],
            'Personnel' => ['read', 'create', 'update', 'delete', 'zip_download'],
            'Piece' => ['read', 'create', 'update', 'delete'],
            'Role' => ['read', 'create', 'update', 'delete'],
            'Setting' => ['read', 'update'],
        ];

        foreach ($modelDefinitions as $modelKey => $actions) {
            foreach ($actions as $action) {
                $gateName = strtolower($modelKey).'.'.strtolower($action);
                Gate::define($gateName, function (User $user) use ($modelKey, $action) {
                    return $user->hasPermission($modelKey, $action);
                });
            }
        }
    }
}
