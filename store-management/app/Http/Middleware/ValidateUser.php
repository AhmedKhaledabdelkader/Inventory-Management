<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ValidateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $rules=[
            'name' => 'required|string|min:3|max:255|unique:users,name',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6',
            'location_code' => 'nullable|string|max:10',
            'status' => 'required|in:active,blocked',
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        return $next($request);


        
    }
}
