<?php

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

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

test('reset password notification implements ShouldQueue and builds valid mail message', function () {
    $user = User::factory()->create([
        'name' => 'Jean Dupont',
        'email' => 'jean.dupont@entreprise.com',
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

test('password reset fails with invalid token', function () {
    $user = User::factory()->create([
        'email' => 'jean.valjean@entreprise.com',
        'password' => 'OldPassword123!',
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
