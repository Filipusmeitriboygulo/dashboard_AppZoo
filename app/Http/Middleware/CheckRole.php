<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return redirect('/login')->withErrors(['email' => 'Your account is inactive.']);
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
