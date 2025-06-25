<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware CoachMiddleware
 *
 * Ce middleware vérifie si l'utilisateur connecté est un coach.
 * Il est utilisé pour protéger les routes accessibles uniquement aux coachs.
 *
 * Fonctionnalités :
 * - Vérifie si l'utilisateur est connecté
 * - Vérifie si l'utilisateur a le rôle 'coach'
 * - Redirige vers une page d'erreur 403 si l'accès n'est pas autorisé
 */
class CoachMiddleware
{
    /**
     * Gère la requête entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si l'utilisateur est connecté et est un coach
        if (!$request->user() || !$request->user()->isCoach()) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
