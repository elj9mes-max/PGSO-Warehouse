<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Strip server banner and fingerprinting headers
        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
            header_remove('Server');
        }
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // 1. Content Security Policy (CSP)
        // Defends against Cross-Site Scripting (XSS), data injection, and malicious asset execution
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://challenges.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net",
            "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net data:",
            "img-src 'self' data: https: blob:",
            "connect-src 'self' https://challenges.cloudflare.com",
            "frame-src 'self' https://challenges.cloudflare.com",
            "frame-ancestors 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            'upgrade-insecure-requests',
        ];
        $response->headers->set('Content-Security-Policy', implode('; ', $csp));

        // 2. Clickjacking Defense (for legacy browsers; modern browsers honor frame-ancestors 'self')
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 3. MIME-type sniffing defense
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 4. Referrer Information Leakage Defense
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 5. Browser Hardware & API Permissions Restrictions
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=()');

        // 6. Cross-Origin Protection
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // 7. HTTP Strict Transport Security (HSTS)
        if ($request->isSecure() || $request->header('X-Forwarded-Proto') === 'https' || app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // 8. Cache-Control for Authentication and Sensitive Application Pages
        if ($this->shouldPreventCaching($request)) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        // 9. Strict Cookie Security Hardening (HttpOnly, SameSite, Secure)
        $this->hardenCookies($request, $response);

        return $response;
    }

    /**
     * Determine if the response should be restricted from browser/shared caching.
     */
    protected function shouldPreventCaching(Request $request): bool
    {
        if ($request->user() !== null) {
            return true;
        }

        if ($request->is('/', 'login', 'signup', 'admin/*', 'staff/*')) {
            return true;
        }

        return false;
    }

    /**
     * Enforce strict cookie security attributes on outgoing response cookies.
     */
    protected function hardenCookies(Request $request, Response $response): void
    {
        $isHttps = $request->isSecure()
            || $request->header('X-Forwarded-Proto') === 'https'
            || app()->environment('production')
            || (bool) config('session.secure', false);

        foreach ($response->headers->getCookies() as $cookie) {
            $sameSite = $cookie->getSameSite() ?: 'lax';

            // Prevent SameSite=None without Secure flag (RFC 6265bis)
            if (strtolower((string) $sameSite) === 'none' && ! $isHttps) {
                $sameSite = 'lax';
            }

            // Enforce HttpOnly on session and sensitive cookies
            $isHttpOnly = $cookie->isHttpOnly();
            if ($cookie->getName() === config('session.cookie') || $cookie->getName() === 'XSRF-TOKEN') {
                $isHttpOnly = true;
            }

            $secure = $cookie->isSecure() || $isHttps;

            $newCookie = new Cookie(
                $cookie->getName(),
                $cookie->getValue(),
                $cookie->getExpiresTime(),
                $cookie->getPath(),
                $cookie->getDomain(),
                $secure,
                $isHttpOnly,
                $cookie->isRaw(),
                $sameSite,
                $cookie->isPartitioned()
            );

            $response->headers->setCookie($newCookie);
        }
    }
}
