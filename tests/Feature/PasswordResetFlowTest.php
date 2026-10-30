<?php

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

test('login screen displays forgot password link', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('Mot de passe oublié ?');
    $response->assertSee(route('password.request'));
});

test('guest can view forgot password screen', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
    $response->assertSee('Mot de passe oublié');
    $response->assertSee('Envoyer le lien de réinitialisation');
    $response->assertSee(route('login'));
});

test('forgot password screen validates email address', function () {
    $response = $this->post('/forgot-password', [
        'email' => '',
    ]);

    $response->assertSessionHasErrors('email');
});

test('password reset notification is queued when valid email is submitted', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'agent.test@entreprise.com',
        'statut' => true,
    ]);

    $response = $this->post('/forgot-password', [
        'email' => 'agent.test@entreprise.com',
    ]);

    $response->assertSessionHas('status');
    $response->assertSessionHasNoErrors();

    Notification::assertSentTo(
        $user,
        ResetPasswordNotification::class,
        function (ResetPasswordNotification $notification) {
            expect($notification)->toBeInstanceOf(ShouldQueue::class)
                ->and($notification->token)->not->toBeEmpty();

            return true;
        }
    );
});

test('immediate second request for password reset returns informative notice and does not send duplicate email', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'agent.rapid@entreprise.com',
        'statut' => true,
    ]);

    // Première soumission : lien généré et expédié
    $firstResponse = $this->post('/forgot-password', [
        'email' => 'agent.rapid@entreprise.com',
    ]);

    $firstResponse->assertSessionHas('status');
    $firstResponse->assertSessionHasNoErrors();
    Notification::assertSentTimes(ResetPasswordNotification::class, 1);

    // Seconde soumission immédiate (laps de temps très court, même e-mail / même IP)
    $secondResponse = $this->post('/forgot-password', [
        'email' => 'agent.rapid@entreprise.com',
    ]);

    // Ne doit PAS renvoyer "Aucun compte actif trouvé"
    $secondResponse->assertSessionHasNoErrors();
    // Doit renvoyer un message d'information invitant à vérifier la boîte et les spams
    $secondResponse->assertSessionHas('info', function ($message) {
        return str_contains($message, 'Un e-mail de réinitialisation vous a déjà été envoyé')
            && str_contains($message, 'spams');
    });

    // Ne doit pas avoir renvoyé un second e-mail
    Notification::assertSentTimes(ResetPasswordNotification::class, 1);
});

test('second request after queue failure purges failed state and allows resend', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'agent.failed@entreprise.com',
        'statut' => true,
    ]);

    // Simuler une demande précédente enregistrée dans password_reset_tokens
    $tokenTime = Carbon::now()->subSeconds(10);
    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => Hash::make('failed-token-123'),
        'created_at' => $tokenTime,
    ]);

    // Simuler l'échec de transmission dans la table failed_jobs
    DB::table('failed_jobs')->insert([
        'uuid' => (string) Str::uuid(),
        'connection' => 'database',
        'queue' => 'default',
        'payload' => json_encode([
            'displayName' => 'App\\Notifications\\ResetPasswordNotification',
            'data' => [
                'command' => serialize(new SendQueuedNotifications(
                    collect([$user]),
                    new ResetPasswordNotification('failed-token-123'),
                    ['mail']
                )),
            ],
        ]),
        'exception' => 'Connection to smtp.gmail.com failed',
        'failed_at' => Carbon::now(),
    ]);

    // Nouvelle soumission : doit détecter l'échec précédent et renvoyer
    $response = $this->post('/forgot-password', [
        'email' => $user->email,
    ]);

    $response->assertSessionHas('status');
    $response->assertSessionHasNoErrors();
    Notification::assertSentTimes(ResetPasswordNotification::class, 1);
});

test('forgot password rejects inactive user with proper message', function () {
    Notification::fake();

    User::factory()->create([
        'email' => 'inactive.agent@entreprise.com',
        'statut' => false,
    ]);

    $response = $this->post('/forgot-password', [
        'email' => 'inactive.agent@entreprise.com',
    ]);

    $response->assertSessionHasErrors([
        'email' => 'Aucun compte actif n\'a été trouvé avec cette adresse e-mail.',
    ]);

    Notification::assertNothingSent();
});

test('reset password notification implements ShouldQueue and builds valid mail message', function () {
    $user = User::factory()->create([
        'name' => 'Jean Dupont',
        'email' => 'jean.dupont@entreprise.com',
        'statut' => true,
    ]);

    $notification = new ResetPasswordNotification('test-sample-token-12345');
    expect($notification)->toBeInstanceOf(ShouldQueue::class)
        ->and($notification->tries)->toBe(5)
        ->and($notification->backoff)->toBe([5, 15, 30])
        ->and($notification->timeout)->toBe(60);

    $mail = $notification->toMail($user);

    expect($mail->subject)->toContain('Réinitialisation de votre mot de passe')
        ->and($mail->greeting)->toContain('Jean Dupont')
        ->and($mail->actionText)->toBe('Réinitialiser mon mot de passe')
        ->and($mail->actionUrl)->toContain('reset-password/test-sample-token-12345')
        ->and($mail->actionUrl)->toContain(urlencode('jean.dupont@entreprise.com'));
});

test('guest can view reset password screen with token', function () {
    $response = $this->get('/reset-password/sample-token-xyz?email=agent@entreprise.com');

    $response->assertStatus(200);
    $response->assertSee('Nouveau mot de passe');
    $response->assertSee('Mettre à jour le mot de passe');
    $response->assertSee('sample-token-xyz');
    $response->assertSee('agent@entreprise.com');
});

test('user can reset password with valid token and is redirected to login with status', function () {
    $user = User::factory()->create([
        'email' => 'jean.valjean@entreprise.com',
        'password' => 'OldPassword123!',
        'statut' => true,
    ]);

    $token = Password::createToken($user);

    $response = $this->post('/reset-password', [
        'token' => $token,
        'email' => 'jean.valjean@entreprise.com',
        'password' => 'NewStrongPassword2026!',
        'password_confirmation' => 'NewStrongPassword2026!',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHas('status');

    $user->refresh();
    expect(Hash::check('NewStrongPassword2026!', $user->password))->toBeTrue();
});

test('user can reset password with mixed-case and trailing whitespace email', function () {
    $user = User::factory()->create([
        'email' => 'victor.hugo@entreprise.com',
        'password' => 'OldPassword123!',
        'statut' => true,
    ]);

    $token = Password::createToken($user);

    $response = $this->post('/reset-password', [
        'token' => $token,
        'email' => ' Victor.Hugo@Entreprise.Com ',
        'password' => 'NewStrongPassword2026!',
        'password_confirmation' => 'NewStrongPassword2026!',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHas('status');

    $user->refresh();
    expect(Hash::check('NewStrongPassword2026!', $user->password))->toBeTrue();
});

test('reset password rejects inactive account', function () {
    $user = User::factory()->create([
        'email' => 'desactive@entreprise.com',
        'password' => 'OldPassword123!',
        'statut' => false,
    ]);

    $token = Password::createToken($user);

    $response = $this->from('/reset-password/'.$token)->post('/reset-password', [
        'token' => $token,
        'email' => 'desactive@entreprise.com',
        'password' => 'NewStrongPassword2026!',
        'password_confirmation' => 'NewStrongPassword2026!',
    ]);

    $response->assertSessionHasErrors([
        'email' => 'Aucun utilisateur actif n\'a été trouvé avec cette adresse e-mail.',
    ]);
});

test('password reset fails with invalid token', function () {
    $user = User::factory()->create([
        'email' => 'jean.valjean@entreprise.com',
        'password' => 'OldPassword123!',
        'statut' => true,
    ]);

    $response = $this->from('/reset-password/invalid-token')->post('/reset-password', [
        'token' => 'invalid-token',
        'email' => 'jean.valjean@entreprise.com',
        'password' => 'NewStrongPassword2026!',
        'password_confirmation' => 'NewStrongPassword2026!',
    ]);

    $response->assertSessionHasErrors('email');
    $user->refresh();
    expect(Hash::check('OldPassword123!', $user->password))->toBeTrue();
});

test('authenticated user clicking reset password link from email is not redirected to home and can view reset screen', function () {
    $user = User::factory()->create([
        'email' => 'auth.tester@entreprise.com',
        'statut' => true,
    ]);

    // L'utilisateur est actuellement connecté dans son navigateur
    $response = $this->actingAs($user)->get('/reset-password/sample-token-abc?email=auth.tester@entreprise.com');

    // Ne doit PAS être redirigé vers l'accueil (/)
    $response->assertStatus(200);
    $response->assertSee('Nouveau mot de passe');
    $response->assertSee('sample-token-abc');
    $response->assertSee('auth.tester@entreprise.com');

    // L'ancienne session a été déconnectée pour sécuriser le flux
    $this->assertGuest();
});

test('authenticated user can reset password and is redirected to login', function () {
    $user = User::factory()->create([
        'email' => 'auth.reset@entreprise.com',
        'password' => 'OldPassword123!',
        'statut' => true,
    ]);

    $token = Password::createToken($user);

    $response = $this->actingAs($user)->post('/reset-password', [
        'token' => $token,
        'email' => 'auth.reset@entreprise.com',
        'password' => 'BrandNewPassword2026!',
        'password_confirmation' => 'BrandNewPassword2026!',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHas('status');

    $user->refresh();
    expect(Hash::check('BrandNewPassword2026!', $user->password))->toBeTrue();
    $this->assertGuest();
});
