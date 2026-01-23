<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlaneController;

Route::get('/planes/fetch', [PlaneController::class, 'fetch']); // save from OpenSky
Route::get('/planes', [PlaneController::class, 'index']);        
