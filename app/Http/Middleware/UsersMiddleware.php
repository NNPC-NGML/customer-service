<?php

namespace App\Http\Middleware;

use Closure;
use Skillz\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UsersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = (new UserService)->getRequest('get', 'scope/user');

        if (!$response->ok()) {
            abort(401, 'unauthorized');
        }
        Auth::setUser(new \App\Models\User($response->json()));

        return $next($request);
    }
}

