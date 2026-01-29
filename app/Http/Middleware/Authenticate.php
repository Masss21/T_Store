<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // If request expects JSON (AJAX request), return null to trigger 401
        if ($request->expectsJson()) {
            return null;
        }

        // For normal requests, redirect to login
        return route('login');
    }

    /**
     * Handle an unauthenticated user.
     *
     * Override untuk memberikan response JSON yang proper untuk AJAX requests
     */
    protected function unauthenticated($request, array $guards)
    {
        // Jika request adalah AJAX/JSON
        if ($request->expectsJson()) {
            abort(response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.',
                'redirect' => route('login')
            ], 401));
        }

        // Jika bukan AJAX, redirect ke login page
        throw new \Illuminate\Auth\AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectTo($request)
        );
    }
}