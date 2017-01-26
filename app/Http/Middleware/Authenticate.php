<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Monkey\Environment\Environment;

class Authenticate {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }

            if (Environment::isMultiDomain()) {
                Session::put('url.intended', '/');
                return redirect()->to('auth/login');
            }
            return redirect()->guest('auth/login');
        }

        return $next($request);
    }
}
