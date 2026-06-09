<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventoController;

// Home
Route::get('/', [EventoController::class, 'index']);

// About
Route::get('/about', function () {
    return view('pages.about');
});

// Contact
Route::get('/contact', function (){
    return view('pages.contact');
});

