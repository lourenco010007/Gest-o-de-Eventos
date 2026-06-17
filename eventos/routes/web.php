<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventoController;

//Home
Route::get('/', function() {
    return view('pages.home');
});
// Dados do dashboard (JSON)
Route::get('/dashboard/data', [EventoController::class, 'dashboardData']);

// evento
Route::get('/eventos', [EventoController::class, 'index']);
Route::get('/eventos/create', [EventoController::class, 'create']);
Route::post('/eventos', [EventoController::class, 'store']);
Route::get('/eventos/{id}', [EventoController::class, 'show']);


// About
Route::get('/about', function () {
    return view('pages.about');
});

// Contact
Route::get('/contact', function (){
    return view('pages.contact');
});


