<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Tymon\JWTAuth\Http\Middleware\Authenticate;
use App\Http\Middleware\JwtFromCookie;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'jwt' => Authenticate::class,
            'jwt.cookie' => JwtFromCookie::class,
        ]);

        $middleware->encryptCookies(except: [
            'auth_token',
        ]);

        // Aplica o middleware de leitura de cookie em todas as rotas da API
        // IMPORTANTE: Deve ser executado ANTES do middleware de autenticação
        $middleware->api(prepend: [
            JwtFromCookie::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
