<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Hide PHP version (X-Powered-By: PHP/8.4.x)
        header_remove('X-Powered-By');
        $response->headers->remove('X-Powered-By');

        // Clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');
        // MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        // Extras worth having
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // HSTS (only over HTTPS; works behind Cloudflare thanks to TrustProxies)
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // CSP: start in Report-Only, switch header name once the console is clean
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://unpkg.com https://cdn.jsdelivr.net",
            "style-src 'self' 'unsafe-inline' https://unpkg.com https://cdn.jsdelivr.net",
            "img-src 'self' data: https://*.tile.openstreetmap.org",
            "font-src 'self' data:",
            "connect-src 'self'",
            "frame-src https://www.youtube.com https://www.youtube-nocookie.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
        ]);
        $response->headers->set('Content-Security-Policy-Report-Only', $csp);

        return $response;
    }
}