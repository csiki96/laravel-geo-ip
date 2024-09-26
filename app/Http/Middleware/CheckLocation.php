<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLocation
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next): Response
    {

        $location = geoip()->getLocation($request->ip());

        \Log::info('User Location:', ['ip' => $request->ip(), 'location' => $location]);

        if ($location->state_name == 'California' && !$this->isSearchEngine($request->userAgent())) {
            abort(404, 'Not available in your region.');
        }

        return $next($request);
    }

    protected function isSearchEngine($userAgent): false|int
    {
        return preg_match('/bot|crawl|slurp|spider/i', $userAgent);
    }
}
