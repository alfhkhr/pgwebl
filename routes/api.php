<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

route::get('/points', [ApiController::class, 'points'])->name('api.points');
route::get('/point/{id}', [ApiController::class, 'point'])->name('api.point');
route::get('/polylines', [ApiController::class, 'polylines'])->name('api.polylines');
route::get('/polyline/{id}', [ApiController::class, 'polyline'])->name('api.polyline');
route::get('/polygons', [ApiController::class, 'polygons'])->name('api.polygons');
route::get('/polygon/{id}', [ApiController::class, 'polygon'])->name('api.polygon');

