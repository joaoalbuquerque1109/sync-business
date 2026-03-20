<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'name' => config('app.name'),
        'status' => 'ok',
        'api' => url('/api/v1'),
    ]);
});

Route::get('/{any}', function () {
    $spaIndex = public_path('spa/index.html');

    abort_unless(file_exists($spaIndex), 503, 'Frontend build not found. Run npm run build.');

    return response()->file($spaIndex);
})->where('any', '^(?!api|up|storage).*$');
