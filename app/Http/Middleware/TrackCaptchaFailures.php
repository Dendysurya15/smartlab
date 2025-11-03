<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;


class TrackCaptchaFailures
{
    protected $attemptsLimit = 3;
    protected $lockoutTime = 60; // Lockout time in minutes

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $attemptsKey = "captcha_attempts:{$ip}";

        // Retrieve the number of attempts
        $attempts = Cache::get($attemptsKey, 0);

        if ($attempts >= $this->attemptsLimit) {
            // If the limit is exceeded, block the IP by responding with an error or redirecting
            return response()->json(['message' => 'Too many failed attempts.'], 429);
        }

        // Increment attempts count and set an expiration time
        Cache::put($attemptsKey, $attempts + 1, $this->lockoutTime);

        return $next($request);
    }
}
