<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // TLS terminates at the Cloudflare tunnel, so the request reaches the
        // container as plain HTTP. Without trusting the proxy's
        // X-Forwarded-Proto every generated URL comes out http:// on an
        // https:// page and the browser blocks it as mixed content. Safe to
        // trust any proxy here: the container is only reachable through the
        // tunnel and the internal Docker network.
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
