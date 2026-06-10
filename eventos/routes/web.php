<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventoController;

//Home
Route::get('/', function() {
    return view('pages.home');
});


// evento
Route::get('/eventos', [EventoController::class, 'index']);
// Dados do dashboard (JSON)
Route::get('/dashboard/data', [EventoController::class, 'dashboardData']);

// About
Route::get('/about', function () {
    return view('pages.about');
});

// Contact
Route::get('/contact', function (){
    return view('pages.contact');
});

