<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class BlockIpAfterFailedAttempts
{
    const MAX_ATTEMPTS = 3;
    const BLOCK_DURATION = 3600; // 1 hour in seconds

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
        $failedAttempts = Cache::get($ip . '_failed_attempts', 0);
        $isBlocked = Cache::has($ip . '_blocked');

        // Check if the IP is blocked
        if ($isBlocked) {
            return redirect()->route('blocked');
        }

        // Process the request
        $response = $next($request);

        // Increment failed attempts for 401 responses
        if ($response->getStatusCode() == 401) {
            $failedAttempts++;
            Cache::put($ip . '_failed_attempts', $failedAttempts, self::BLOCK_DURATION);

            if ($failedAttempts >= self::MAX_ATTEMPTS) {
                Cache::put($ip . '_blocked', true, self::BLOCK_DURATION);
            }
        } else {
            // Clear failed attempts if the response is not 401
            Cache::forget($ip . '_failed_attempts');
        }

        return $response;
    }
}
