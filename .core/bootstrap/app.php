<?php

use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/*
 * The Laravel app lives in ".core"; the web root (index.php, css, js,
 * images, media) is the folder above it, so public_path() points there.
 */
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Cloudflare sits in front of the site.
        $middleware->trustProxies(at: '*');

        $middleware->append(SecurityHeaders::class);

        // Logged-in users that open /login go straight to the admin page.
        $middleware->redirectUsersTo(fn () => route('admin.index'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();

$app->usePublicPath(dirname(__DIR__, 2));

return $app;
