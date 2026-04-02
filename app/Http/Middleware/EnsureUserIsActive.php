<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->is_active) {
            $request->user()->tokens()->delete();
            return response()->json([
                'message' => 'Your account has been deactivated. Please contact HR.',
            ], 403);
        }

        return $next($request);
    }
}
