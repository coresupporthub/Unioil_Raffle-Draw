<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Set Strict Transport Security (HSTS) header
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $nonce = bin2hex(random_bytes(16));

        // Define Content Security Policy (CSP) with the nonce
        $csp = "default-src 'self'; "
             . "script-src 'self' 'nonce-{$nonce}' https://ajax.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://cdn.datatables.net; "
             . "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://cdn.datatables.net https://rsms.me; "
             . "img-src 'self' data: https://cdn.jsdelivr.net; "
             . "font-src 'self' https://cdnjs.cloudflare.com https://fonts.googleapis.com https://rsms.me https://cdn.jsdelivr.net; "
             . "connect-src 'self'; "
             . "object-src 'none'; "
             . "frame-src 'none'; "
             . "child-src 'none'; "
             . "manifest-src 'self'; "
             . "form-action 'self'; "
             . "upgrade-insecure-requests; "
             . "block-all-mixed-content";

        // Add the CSP header
        $response->headers->set('Content-Security-Policy', $csp);
        // Set other security headers
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}
