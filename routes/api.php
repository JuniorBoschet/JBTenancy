<?php

use Illuminate\Support\Facades\Route;

Route::middleware('tenant.identify')->group(function () {
    Route::get('/`login`', fn () => response()->json(['msg' => 'Olá, tenant!']));
});


