<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'isSupervisor' => \App\Http\Middleware\VerifyIsSupervisor::class,
            'isAdmin' => \App\Http\Middleware\IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ukuran total file/foto yang diunggah terlalu besar melebihi batas upload server (post_max_size).'
                ], 413);
            }

            $url = url()->previous() ?: route('laporan_kegiatan.index');
            return redirect($url)->with('error', 'Ukuran total file atau foto yang Anda unggah terlalu besar dan melebihi batas upload server (post_max_size). Silakan kurangi ukuran/jumlah file atau sesuaikan pengaturan post_max_size di cPanel hosting.');
        });
    })->create();
