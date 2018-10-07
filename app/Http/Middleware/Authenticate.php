<?php

namespace App\Http\Middleware;


use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * @var Guard
     */
    private $guard;

    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    public function handle(Request $request, $next)
    {
        $userCookie = $request->cookie('user');
        if ($userCookie !== null) {
            $this->guard->setUser(User::find($userCookie));
        }

        return $next($request);
    }
}
