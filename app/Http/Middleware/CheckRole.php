<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'প্রবেশ করার জন্য অনুগ্রহ করে লগইন করুন।');
        }

        $user = auth()->user();

        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'আপনার একাউন্টটি সাময়িকভাবে নিষ্ক্রিয় রয়েছে। অনুগ্রহ করে অ্যাডমিনের সাথে যোগাযোগ করুন।');
        }

        // Admin has access to all routes
        if ($user->isAdmin()) {
            return $next($request);
        }

        // If specific roles required, check if user has any of them
        if (!empty($roles) && !$user->hasRole($roles)) {
            abort(403, 'এই সেকশনে প্রবেশের জন্য আপনার অ্যাকাউন্টের অনুমতি নেই (Access Denied)।');
        }

        return $next($request);
    }
}
