<?php

test('responses contain essential security headers', function () {
    $response = $this->get('/');

    $response->assertStatus(200);

    // 1. Content Security Policy (Medium ZAP finding)
    $response->assertHeader('Content-Security-Policy');
    $csp = $response->headers->get('Content-Security-Policy');
    expect($csp)->toContain("default-src 'self'")
        ->toContain('frame-ancestors \'self\'')
        ->toContain("object-src 'none'")
        ->toContain('upgrade-insecure-requests');

    // 2. Clickjacking defense (Medium ZAP finding)
    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');

    // 3. MIME-type sniffing defense (Low ZAP finding)
    $response->assertHeader('X-Content-Type-Options', 'nosniff');

    // 4. Referrer Policy & Permissions Policy
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('Permissions-Policy');
    $response->assertHeader('Cross-Origin-Opener-Policy', 'same-origin');
    $response->assertHeader('Cross-Origin-Resource-Policy', 'same-origin');

    // 5. Cache-Control on sensitive/auth routes (Informational ZAP finding)
    $cacheControl = $response->headers->get('Cache-Control');
    expect($cacheControl)->toContain('no-store')
        ->toContain('no-cache');
});

test('hsts header is sent when request is secure', function () {
    $response = $this->get('https://localhost/');

    $response->assertStatus(200);
    $response->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
});

test('hsts header is sent when request has x-forwarded-proto https', function () {
    $response = $this->withHeaders([
        'X-Forwarded-Proto' => 'https',
    ])->get('/');

    $response->assertStatus(200);
    $response->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
});

test('outgoing cookies enforce httponly and samesite flags', function () {
    $response = $this->get('/');

    $response->assertStatus(200);

    $cookies = $response->headers->getCookies();
    expect($cookies)->not->toBeEmpty();

    foreach ($cookies as $cookie) {
        expect($cookie->isHttpOnly())->toBeTrue();
        expect(strtolower((string) $cookie->getSameSite()))->toBeIn(['lax', 'strict']);
    }
});

test('login route responds to both / and /login via GET', function () {
    $this->get('/')->assertOk()->assertSee('Warehouse Inventory');
    $this->get('/login')->assertOk()->assertSee('Warehouse Inventory');
});

test('login response does not disclose suspicious html comments', function () {
    $response = $this->get('/login');

    $response->assertOk();
    $content = $response->getContent();

    expect($content)->not->toContain('<!-- Password -->')
        ->not->toContain('<!-- Login Form -->')
        ->not->toContain('<!-- Email or Employee ID -->')
        ->not->toContain('<!-- Security Notice -->');
});
