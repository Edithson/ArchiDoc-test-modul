<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function store(Request $request): RedirectResponse
    {
        $rawEmail = (string) $request->input('email');
        $normalizedEmail = trim(strtolower($rawEmail));

        // Inject normalized email for validation
        $request->merge(['email' => $normalizedEmail]);

        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Veuillez saisir votre adresse e-mail.',
            'email.email' => 'Veuillez saisir une adresse e-mail valide.',
        ]);

        // Vérifier l'existence d'un compte actif avec cette adresse e-mail
        $user = User::whereRaw('LOWER(email) = ?', [$normalizedEmail])->first();

        if (! $user || ! $user->statut) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'Aucun compte actif n\'a été trouvé avec cette adresse e-mail.',
            ]);
        }

        // Align with DB email casing so Password broker finds exact user
        $email = $user->email;
        $request->merge(['email' => $email]);

        // Vérifier l'état de la demande précédente pour cet utilisateur
        $existingToken = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        $throttleSeconds = (int) config('auth.passwords.users.throttle', 60);

        if ($existingToken && $existingToken->created_at) {
            $createdAt = Carbon::parse($existingToken->created_at);
            $secondsSinceCreation = $createdAt->diffInSeconds(now());

            // Si un jeton existe déjà dans la fenêtre de limitation
            if ($secondsSinceCreation < $throttleSeconds) {
                // Vérifier si l'envoi précédent a échoué dans la file d'attente (failed_jobs)
                $hasFailedJob = DB::table('failed_jobs')
                    ->where('payload', 'like', '%ResetPasswordNotification%')
                    ->where(function ($query) use ($email, $user) {
                        $query->where('payload', 'like', '%'.$email.'%')
                            ->orWhere('payload', 'like', '%"id";i:'.$user->id.'%')
                            ->orWhere('payload', 'like', '%"id":'.$user->id.'%');
                    })
                    ->where('failed_at', '>=', $createdAt->toDateTimeString())
                    ->exists();

                // Si la tentative précédente n'a pas échoué, on n'envoie pas de nouveau mail et on informe l'utilisateur
                if (! $hasFailedJob) {
                    return back()->with('info', 'Un e-mail de réinitialisation vous a déjà été envoyé. Veuillez vérifier votre boîte de réception (et vos courriers indésirables / spams) avant de renouveler votre demande.');
                }

                // Si la tentative précédente a échoué, on purge l'ancien jeton et les jobs en échec pour permettre une nouvelle tentative
                DB::table('password_reset_tokens')->where('email', $email)->delete();
                DB::table('failed_jobs')
                    ->where('payload', 'like', '%ResetPasswordNotification%')
                    ->where(function ($query) use ($email, $user) {
                        $query->where('payload', 'like', '%'.$email.'%')
                            ->orWhere('payload', 'like', '%"id";i:'.$user->id.'%')
                            ->orWhere('payload', 'like', '%"id":'.$user->id.'%');
                    })
                    ->delete();
            }
        }

        // Tentative d'envoi du lien via le broker Laravel
        $status = Password::sendResetLink(['email' => $email]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Un lien de réinitialisation du mot de passe vous a été envoyé par e-mail.');
        }

        if ($status === Password::RESET_THROTTLED) {
            return back()->with('info', 'Un e-mail de réinitialisation vous a déjà été envoyé. Veuillez vérifier votre boîte de réception (et vos courriers indésirables / spams) avant de renouveler votre demande.');
        }

        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'Aucun compte actif n\'a été trouvé avec cette adresse e-mail.',
        ]);
    }
}
