<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

route::get('/points', [ApiController::class, 'points'])->name('api.points');
route::get('/polylines', [ApiController::class, 'polylines'])->name('api.polylines');
route::get('/polygons', [ApiController::class, 'polygons'])->name('api.polygons');

