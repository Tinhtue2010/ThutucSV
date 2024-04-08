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
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $role
     *
     * @return  mixed
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRoles = Auth::user()->roles->pluck('name')->toArray();

        switch ($role) {
            case 'studentManager':
                if (in_array('studentManager', $userRoles)
                    || in_array('role1', $userRoles)
                    || in_array('role2', $userRoles)
                ) {
                    return $next($request);
                }
                break;

            case 'khoaManager':
                if (in_array('khoaManager', $userRoles)
                    || in_array('role3', $userRoles)
                    || in_array('role4', $userRoles)
                ) {
                    return $next($request);
                }
                break;

            case 'classManager':
                if (in_array('classManager', $userRoles)
                    || in_array('role5', $userRoles)
                    || in_array('role6', $userRoles)
                ) {
                    return $next($request);
                }
                break;

            default:
                if (in_array('defaultRole', $userRoles)) {
                    return $next($request);
                }
                break;
        }

        abort(404);
    }
}
