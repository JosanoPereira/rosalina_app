<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Genero;
use App\Models\Passageiro;
use App\Models\Pessoa;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $generos = Genero::all();
        return view('auth.register', [
            'generos' => $generos,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'apelido' => ['required', 'string', 'max:255'],
            'generos_id' => ['required', 'numeric', 'exists:' . Genero::class . ',id'],
            'bi' => ['nullable', 'string', 'max:14'],
            'telefone' => ['nullable', 'string', 'max:15'],
            'nascimento' => ['required', 'date'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        try {
            DB::beginTransaction();
            $resNome = explode(' ', $request->nome);
            $resApelido = explode(' ', $request->apelido);


            $request->merge([
                'name' => strtolower(remover_acentos($resNome[0]) . '.' . remover_acentos($resApelido[count($resApelido) - 1])),
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $pessoa = Pessoa::create([
                'nome' => $request->nome,
                'apelido' => $request->apelido,
                'bi' => $request->bi?? null,
                'telefone' => $request->telefone?? null,
                'nascimento' => $request->nascimento,
                'generos_id' => $request->generos_id,
            ]);

            $passageiro = Passageiro::create([
                'users_id' => $user->id,
                'pessoas_id' => $pessoa->id,
                'nif' => $request->bi ?? null,
            ]);

            $user->assignRole('Passageiro');

            DB::commit();
            return redirect(route('login', absolute: false));
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao processar o registo. Por favor, tente novamente.');
        }
    }
}
