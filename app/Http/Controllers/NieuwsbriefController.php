<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NieuwsbriefController extends Controller
{
    /**
     * Schrijft in voor de nieuwsbrief. De kortingscode zelf wordt bewust
     * niet teruggegeven: die staat alleen in het account van dit
     * e-mailadres, zodat niemand codes op andermans adres kan ophalen.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $gegevens = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ], [
            'email.required' => 'Vul je e-mailadres in.',
            'email.email'    => 'Dit is geen geldig e-mailadres.',
        ]);

        $inschrijving = NewsletterSubscriber::inschrijven($gegevens['email']);
        $eigenAccount = $request->user() && mb_strtolower($request->user()->email) === $inschrijving->email;

        if ($request->expectsJson()) {
            return response()->json([
                'ingeschreven'  => true,
                'eigen_account' => $eigenAccount,
            ]);
        }

        return back()->with('nieuwsbrief', $eigenAccount ? 'account' : 'gast');
    }
}
