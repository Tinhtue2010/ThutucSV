<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request                                                                          $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        if (Auth::check()) {
            if (isset($role)) {
                switch ($role) {
                    case "studentManager" && (Role(0) || Role(4) || Role(5)):
                    case "khoaManager" && (Role(0) || Role(3) || Role(6)):
                    case "classManager" && (Role(0) || Role(2) || Role(4)):
                    case Role($role):
                        return $next($request);
                        break;
                    default:
                        abort(404);
                        break;
                }
            }

            if (!isset($role) && !Role(1)) {
                return $next($request);
            }

            abort(404);
        } else {
            return redirect()->route('login');
        }

        abort(404);
    }
}
