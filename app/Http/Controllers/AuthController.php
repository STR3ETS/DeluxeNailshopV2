<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Vul je e-mailadres in.',
            'email.email'       => 'Dit is geen geldig e-mailadres.',
            'password.required' => 'Vul je wachtwoord in.',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'De combinatie van e-mailadres en wachtwoord klopt niet.']);
        }

        $request->session()->regenerate();

        return redirect()->intended($request->user()->portalUrl());
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'voornaam'    => ['required', 'string', 'max:100'],
            'achternaam'  => ['required', 'string', 'max:100'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8'],
            'voorwaarden' => ['accepted'],
        ], [
            'voornaam.required'    => 'Vul je voornaam in.',
            'achternaam.required'  => 'Vul je achternaam in.',
            'email.required'       => 'Vul je e-mailadres in.',
            'email.email'          => 'Dit is geen geldig e-mailadres.',
            'email.unique'         => 'Er bestaat al een account met dit e-mailadres.',
            'password.required'    => 'Kies een wachtwoord.',
            'password.min'         => 'Je wachtwoord moet minimaal 8 tekens lang zijn.',
            'voorwaarden.accepted' => 'Ga akkoord met de voorwaarden om een account aan te maken.',
        ]);

        $user = User::create([
            'name'     => trim($validated['voornaam'].' '.$validated['achternaam']),
            'email'    => $validated['email'],
            'password' => $validated['password'],
            'role'     => 'klant',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect($user->portalUrl());
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
