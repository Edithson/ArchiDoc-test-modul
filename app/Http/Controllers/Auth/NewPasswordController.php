<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        // Si une session active est présente dans le navigateur (ex. testeur ou session antérieure),
        // on déconnecte la session pour afficher la page de réinitialisation sans être redirigé vers l'accueil.
        if (Auth::check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return view('auth.reset-password', [
            'request' => $request,
            'token' => (string) ($request->route('token') ?? $request->token ?? request('token')),
            'email' => trim(strtolower((string) ($request->email ?? $request->query('email')))),
        ]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function store(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $rawEmail = (string) $request->input('email');
        $normalizedEmail = trim(strtolower($rawEmail));
        $token = trim((string) $request->input('token'));

        $request->merge([
            'email' => $normalizedEmail,
            'token' => $token,
        ]);

        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'token.required' => 'Le jeton de réinitialisation est manquant.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        // Vérifier l'existence d'un compte actif avec cette adresse e-mail
        $user = User::whereRaw('LOWER(email) = ?', [$normalizedEmail])->first();

        if (! $user || ! $user->statut) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'Aucun utilisateur actif n\'a été trouvé avec cette adresse e-mail.',
            ]);
        }

        // Align with DB email casing so Password::reset() finds the exact user
        $email = $user->email;
        $request->merge(['email' => $email]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                // Tracer dans la boîte noire / journal d'activité
                activity('auth')
                    ->performedOn($user)
                    ->causedBy($user)
                    ->log('Réinitialisation du mot de passe effectuée avec succès via lien sécurisé.');
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's login screen with a success message.
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Votre mot de passe a été réinitialisé avec succès ! Vous pouvez maintenant vous connecter.');
        }

        // If the reset had problems, we will redirect the user back to
        // the form with an appropriate French error message.
        $errorMessage = match ($status) {
            Password::INVALID_TOKEN => 'Ce jeton de réinitialisation est invalide ou a expiré.',
            Password::INVALID_USER => 'Aucun utilisateur actif n\'a été trouvé avec cette adresse e-mail.',
            Password::RESET_THROTTLED => 'Trop de tentatives. Veuillez patienter avant de renouveler votre demande.',
            default => 'Impossible de réinitialiser le mot de passe avec ces informations.',
        };

        return back()->withInput($request->only('email'))->withErrors(['email' => $errorMessage]);
    }
}
