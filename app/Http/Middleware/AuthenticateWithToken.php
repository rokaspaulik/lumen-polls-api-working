<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateWithToken
{
    public function handle($request, Closure $next, string $apiVersion)
    {
        $tokenKey = "API_{$apiVersion}_TOKEN";
        $apiToken = env($tokenKey);

        $token = $request->bearerToken();

        if ($token && hash_equals($apiToken, $token)) {
            return $next($request);
        }

        return response(json_encode([
            'message' => 'Unauthorized.'
        ]), 401)->withHeaders([
            'Content-Type' => 'application/json',
        ]);
    }
}
