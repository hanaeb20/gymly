<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware ClientMiddleware
 *
 * Ce middleware vérifie si l'utilisateur connecté est un client.
 * Il est utilisé pour protéger les routes accessibles uniquement aux clients.
 *
 * Fonctionnalités :
 * - Vérifie si l'utilisateur est connecté
 * - Vérifie si l'utilisateur a le rôle 'client'
 * - Redirige vers une page d'erreur 403 si l'accès n'est pas autorisé
 */
class ClientMiddleware
{
    /**
     * Gère la requête entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si l'utilisateur est connecté et est un client
        if (!$request->user() || !$request->user()->isClient()) {
            abort(403, 'Accès non autorisé. Cette section est réservée aux clients.');
        }

        return $next($request);
    }
}
