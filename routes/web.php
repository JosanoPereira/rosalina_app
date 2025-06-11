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
        $viagens = \App\Models\Viagen::all()->count();
        $bilhetes = \App\Models\Bilhete::all()->count();
        $motoristas = \App\Models\Motorista::all()->count();
        $autocarros = \App\Models\Autocarro::all()->count();
        return view('dashboards.index', [
            'viagens' => $viagens,
            'bilhetes' => $bilhetes,
            'motoristas' => $motoristas,
            'autocarros' => $autocarros,
        ]);
    })->name('dashboard.index');
    Route::get('/dashboard/passageiros', function () {
        $passageiro = \App\Models\Passageiro::all()->where('users_id', auth()->id())->first();
        $bilhetes = 0;
        if ($passageiro){
            $bilhetes = \App\Models\Bilhete::all()->where('passageiros_id', $passageiro->id)->count();
        }
        $viagensFeitas = \App\Models\Bilhete::all()->where('activo', 0)
            ->where('passageiros_id', $passageiro->id)->count();
        return view('dashboards.passageiros.index', [
            'passageiro' => $passageiro,
            'bilhetes' => $bilhetes,
            'viagensFeitas' => $viagensFeitas,
        ]);
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
