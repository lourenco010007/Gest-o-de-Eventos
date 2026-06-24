<?php

use App\Http\Controllers\EventoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventoController::class, 'home']);

Route::get('/eventos', [EventoController::class, 'index']);
Route::get('/eventos/{id}', [EventoController::class, 'show'])->whereNumber('id');
Route::post('/eventos/check-conflito', [EventoController::class, 'checkConflito']);

Route::middleware('auth')->group(function () {
    Route::get('/eventos/create', [EventoController::class, 'create']);
    Route::post('/eventos', [EventoController::class, 'store']);
    Route::get('/eventos/edit/{id}', [EventoController::class, 'edit'])->whereNumber('id');
    Route::put('/eventos/update/{id}', [EventoController::class, 'update'])->whereNumber('id');

    Route::get('/meus-eventos', [EventoController::class, 'myEvents']);
    Route::patch('/meus-eventos/{id}/cancelar', [EventoController::class, 'requestCancel'])->whereNumber('id');
    Route::patch('/meus-eventos/{id}/adiar', [EventoController::class, 'requestPostpone'])->whereNumber('id');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [EventoController::class, 'adminDashboard']);
    Route::post('/admin/users', [EventoController::class, 'adminStoreUser']);
    Route::patch('/admin/eventos/{id}/status', [EventoController::class, 'adminUpdateEventStatus'])->whereNumber('id');
    Route::delete('/eventos/{id}', [EventoController::class, 'destroy'])->whereNumber('id');

    Route::post('/admin/saloes', [EventoController::class, 'adminStoreSalao']);
    Route::put('/admin/saloes/{id}', [EventoController::class, 'adminUpdateSalao'])->whereNumber('id');
    Route::delete('/admin/saloes/{id}', [EventoController::class, 'adminDestroySalao'])->whereNumber('id');
});

Route::get('/dashboard/data', [EventoController::class, 'dashboardData'])->middleware(['auth', 'admin']);

Route::get('/about', fn () => view('pages.about'));
Route::get('/contact', fn () => view('pages.contact'));

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', fn () => auth()->user()?->isAdmin() ? redirect('/admin') : redirect('/meus-eventos'))->name('dashboard');
});
