<?php

namespace App\Http\Middleware;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter as FacadesRateLimiter;
use Illuminate\Validation\ValidationException;

class LoginValidationMiddleware
{
    protected function limiter()
    {
        return FacadesRateLimiter::perMinute(3)->by(request()->ip())->response(function (Request $request) {
            throw new AuthenticationException('Too many login attempts.');
        });
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->limiter()->tooManyAttempts($key = $this->throttleKey($request), 3)) {
            $this->fireLockoutEvent($request);

            $seconds = $this->limiter()->availableIn($key);

            throw ValidationException::withMessages([
                'email' => [trans('auth.throttle', ['seconds' => $seconds])],
            ])->status(429);
        }

        if (auth()->attempt($request->only('email', 'password'))) {
            $this->limiter()->clear($key);

            return $next($request);
        }

        $this->limiter()->hit($key);

        return redirect()->back()->withErrors([
            'email' => 'Invalid email or password.'
        ]);
    }

    protected function throttleKey(Request $request)
    {
        return mb_strtolower($request->input('email')) . '|' . $request->ip();
    }

    protected function fireLockoutEvent(Request $request)
    {
        $event = new Lockout($request);

        event($event);
    }
}
