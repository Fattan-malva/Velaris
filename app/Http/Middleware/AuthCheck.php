<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        // Check if the user is logged in
        if (!$request->session()->has('user_id')) {
            return redirect('/')->with('fail', 'Anda harus login dulu');
        }

        // Retrieve user_role from the session
        $userRole = $request->session()->get('user_role');

        // If user_role is not set, retrieve it from the database and store it in the session
        if (is_null($userRole)) {
            $userId = $request->session()->get('user_id');
            $user = \App\Models\User::find($userId);

            if ($user) {
                $userRole = $user->role;
                $request->session()->put('user_role', $userRole);
            } else {
                return redirect('/')->with('fail', 'User tidak ditemukan');
            }
        }

        // If a specific role is required, check the user's role
        if ($role && $userRole !== $role) {
            return redirect('/')->with('fail', 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}
