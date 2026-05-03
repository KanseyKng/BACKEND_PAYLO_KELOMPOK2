<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBanned
{
    public function handle(Request $request, Closure $next)
    {
        
        if ($request->user() && $request->user()->status === 'banned') {
            return response()->json(['message' => 'Akun Anda telah diblokir.'], 403);
        }
        return $next($request);
    }
}