<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ValidateUpdateUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->route('userId'); // UUID

        $rules = [
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:255',
                Rule::unique('users', 'name')->ignore($userId, 'id'),
            ],

            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($userId, 'id'),
            ],

            'password' => ['sometimes', 'string', 'min:6'],

            'location_code' => ['nullable', 'string', 'max:10'],

            'role_ids' => ['sometimes', 'array'],
            'role_ids.*' => ['uuid', 'exists:roles,id'],
        ];

        $validator = Validator::make($request->all(), $rules);

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