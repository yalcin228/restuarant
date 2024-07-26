<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'staff') {
            if (Gate::denies($permission)) {
                return response()->json(['message' => 'Bu işlem için yetkiniz yok.'], 403);
            }
        }

        return $next($request);
    }
}
