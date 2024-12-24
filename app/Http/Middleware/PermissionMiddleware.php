<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Get the user's permissions
        $permissions = collect($user->permissions)->pluck('name')->toArray(); // Assuming permissions are available on the user model
        // dd($permissions);
        // Define permissions for full access and restricted access
        $fullAccessPermissions = ['user-management', 'user-admin'];
        $restrictedPermissions = ['import-orders', 'import-products', 'import-customers'];

        // Check for full access permissions
        if (array_intersect($permissions, $fullAccessPermissions)) {
            return $next($request);
        }

        // Check for restricted permissions
        if (array_intersect($permissions, $restrictedPermissions)) {
            // Block access to `/users`
            if ($request->is('users')) {
                abort(403, 'Unauthorized');
            }

            return $next($request);
        }

        // Default: Unauthorized
        abort(403, 'Unauthorized');
    }
}
