<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;

//Home
Route::get('/', [EventoController::class, 'index']);

