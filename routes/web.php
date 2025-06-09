<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RotaController;
use App\Http\Controllers\BilheteController;
use App\Http\Controllers\AutocarroController;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\ParadaController;
use App\Http\Controllers\ViagenController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        if (auth()->check() && auth()->user()->hasRole('Passageiro')) {
            return redirect()->route('dashboard.passageiros');
        }
        return redirect()->route('dashboard.index');
    })->name('dashboard');

    // Dashboard routes
    Route::get('/dashboard', function () {
        return view('dashboards.index');
    })->name('dashboard.index');
    Route::get('/dashboard/passageiros', function () {
        return view('dashboards.passageiros.index');
    })->name('dashboard.passageiros');

    // Others routes
    Route::resource('rotas', RotaController::class);
    Route::resource('bilhetes', BilheteController::class);
    Route::resource('autocarros', AutocarroController::class);
    Route::resource('motoristas', MotoristaController::class);
    Route::resource('paradas', ParadaController::class);
    Route::resource('viagens', ViagenController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::fallback(function () {
    return redirect()->route('dashboard');
});
