<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class LogAuthEvents
{
    /**
     * Handle user login event.
     */
    public function handleLogin(Login $event): void
    {
        $user = $event->user;

        activity('auth')
            ->performedOn($user)
            ->causedBy($user)
            ->event('auth.login')
            ->withProperties([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
            ])
            ->log("Connexion réussie de l'utilisateur « {$user->name} » (Matricule: {$user->matricule})");
    }

    /**
     * Handle user failed login event.
     */
    public function handleFailed(Failed $event): void
    {
        $credentials = $event->credentials;
        $attempted = $credentials['email'] ?? ($credentials['matricule'] ?? 'Inconnu');

        activity('auth')
            ->event('auth.failed_login')
            ->withProperties([
                'attempted' => $attempted,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
            ])
            ->log("Échec de tentative de connexion pour l'identifiant « {$attempted} »");
    }

    /**
     * Handle user logout event.
     */
    public function handleLogout(Logout $event): void
    {
        $user = $event->user;

        if ($user) {
            activity('auth')
                ->performedOn($user)
                ->causedBy($user)
                ->event('auth.logout')
                ->withProperties([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Déconnexion de l'utilisateur « {$user->name} »");
        }
    }

    /**
     * Handle user lockout event.
     */
    public function handleLockout(Lockout $event): void
    {
        activity('auth')
            ->event('auth.lockout')
            ->withProperties([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Verrouillage temporaire de sécurité suite à des tentatives répétées');
    }
}
