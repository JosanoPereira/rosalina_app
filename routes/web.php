<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RotaController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard2');

Route::middleware('auth')->group(function () {


    Route::get('/', function () {
        if (auth()->check() && auth()->user()->hasRole('Passageiro')) {
            return view('dashboards.passageiros.index');
        }
        return view('dashboards.index');
    })->name('dashboard');

    Route::resource('rotas', RotaController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::fallback(function () {
    return redirect()->route('dashboard');
});
