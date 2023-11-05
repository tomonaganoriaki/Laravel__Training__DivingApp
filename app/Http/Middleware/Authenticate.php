<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use PhpParser\Node\Stmt\Else_;

class Authenticate extends Middleware
{
    protected $user_route = 'user.login';
    protected $admin_route = 'admin.login';
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->route()->named('admin.*')) {
            return route($this->admin_route);
        } elseif ($request->route()->named('user.*')) {
            return route($this->user_route);
        }
    }
}
