<?php

use Illuminate\Support\Facades\Route;

Route::middleware('tenant.identify')->group(function () {
    Route::get('/`login`', fn () => response()->json(['msg' => 'Olá, tenant!']));
});

Route::middleware(['tenant.identify', 'auth:api'])->group(function () {
    Route::get('/dashboard', fn () => response()->json(['user' => auth()->user()]));
});

