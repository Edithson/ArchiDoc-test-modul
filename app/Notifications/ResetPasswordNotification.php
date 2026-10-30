<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Route;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 5;

    /**
     * The number of seconds to wait before retrying the job on transient network errors.
     */
    public array $backoff = [5, 15, 30];

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 60;

    /**
     * Create a notification instance.
     */
    public function __construct(public string $token) {}

    /**
     * Get the notification's channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $appName = setting('app_name', config('app.name', 'ArchiDoc'));
        $email = $notifiable->getEmailForPasswordReset();
        $resetUrl = Route::has('password.reset')
            ? route('password.reset', ['token' => $this->token, 'email' => $email])
            : url('/reset-password/'.$this->token.'?email='.urlencode((string) $email));

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe — '.$appName)
            ->greeting('Bonjour '.($notifiable->name ?? 'Utilisateur').',')
            ->line('Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation du mot de passe pour votre compte sur '.$appName.'.')
            ->action('Réinitialiser mon mot de passe', $resetUrl)
            ->line('Ce lien de réinitialisation expirera dans '.config('auth.passwords.users.expire', 60).' minutes.')
            ->line('Si vous n\'avez pas demandé cette réinitialisation, aucune action supplémentaire n\'est requise de votre part ; votre mot de passe actuel reste sécurisé et inchangé.')
            ->salutation("Cordialement,\nL'équipe ".$appName);
    }
}
