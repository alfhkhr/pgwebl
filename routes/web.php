<?php

use App\Http\Controllers\PointsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TableController;

Route::get('/', [PointsController::class, 'index']
)->name('map');

Route::get('/table', [TableController::class, 'index'])->name('table');

Route::resource('points', PointsController::class);
