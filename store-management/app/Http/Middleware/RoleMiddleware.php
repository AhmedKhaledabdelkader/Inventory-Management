<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
      
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'error' => 'Unauthenticated'
            ], 401);
        }

        // ✅ Check if user is blocked
        if ($user->status === 'blocked') {
            return response()->json([
                'status' => 'error',
                'error' => 'User is blocked'
            ], 403);
        }

        // ✅ Check roles (many-to-many)
        $hasRole = $user->roles()
            ->whereIn('name', $roles)
            ->exists();
        
       // echo "User roles: " . implode(', ', $user->roles()->pluck('name')->toArray()) . "; Required roles: " . implode(', ', $roles) . "; Has required role? " . ($hasRole ? 'Yes' : 'No');

        if (! $hasRole) {
            return response()->json([
                'status' => 'error',
                'error' => 'Unauthorized (invalid role)'
            ], 403);
        }

        return $next($request);
    }
}