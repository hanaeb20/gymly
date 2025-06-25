<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware GerantMiddleware
 *
 * Ce middleware vérifie si l'utilisateur connecté est un gérant.
 * Il est utilisé pour protéger les routes accessibles uniquement aux gérants de salles.
 *
 * Fonctionnalités :
 * - Vérifie si l'utilisateur est connecté
 * - Vérifie si l'utilisateur a le rôle 'gerant'
 * - Journalise les informations de l'utilisateur pour le débogage
 * - Redirige vers une page d'erreur 403 si l'accès n'est pas autorisé
 */
class GerantMiddleware
{
    /**
     * Gère la requête entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si l'utilisateur est connecté et est un gérant
        if (!$request->user() || !$request->user()->isGerant()) {
            abort(403, 'Accès non autorisé. Cette section est réservée aux gérants.');
        }

        // Journalise les informations de l'utilisateur pour le débogage
        $user = $request->user();
        \Log::info('Utilisateur authentifié', [
            'id' => $user->id,
            'role' => $user->role,
            'isGerant' => $user->isGerant()
        ]);

        return $next($request);
    }
}
