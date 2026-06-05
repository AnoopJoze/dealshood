<?php
// app/Http/Middleware/SecurityHeaders.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // XSS filter (legacy browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Disable browser features we don't use
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Remove server info header
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');

        // Content Security Policy
        $csp = implode('; ', [
    "default-src 'self'",

    "script-src 'self' 'unsafe-inline' 'unsafe-eval' " .
        "https://code.jquery.com " .
        "https://cdn.jsdelivr.net " .
        "https://cdnjs.cloudflare.com " .
        "https://cdn.datatables.net " .
        "https://cdn.ckeditor.com " .
        "https://buttons.github.io " .
        "https://www.googletagmanager.com " .
        "https://www.google-analytics.com",

    "style-src 'self' 'unsafe-inline' " .
        "https://cdn.jsdelivr.net " .
        "https://cdnjs.cloudflare.com " .
        "https://fonts.googleapis.com " .
        "https://cdn.datatables.net",

    "font-src 'self' " .
        "https://cdnjs.cloudflare.com " .
        "https://fonts.gstatic.com " .
        "https://cdn.jsdelivr.net",

    "img-src 'self' data: blob: https: http:",

    "media-src 'self' blob: https:",

    "connect-src 'self' " .
        "https://www.google-analytics.com " .
        "https://analytics.google.com " .
        "https://cdn.jsdelivr.net",
        "https://cdn.ckeditor.com",
        "https://api.github.com",

    "frame-src 'self' " .
        "https://maps.google.com " .
        "https://www.google.com",

    "worker-src 'self' blob:",

    "object-src 'none'",
    "base-uri 'self'",
    "form-action 'self'",
]);
        $response->headers->set('Content-Security-Policy', $csp);

        // HSTS — only on production HTTPS
        if (app()->environment('production')) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        return $response;
    }
}