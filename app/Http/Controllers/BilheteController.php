<?php

namespace App\Http\Controllers;

use App\Models\Bilhete;
use App\Models\Passageiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BilheteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->hasRole('Admin') && !auth()->user()->hasRole('Passageiro')) {
            return redirect()->back()->with('error', 'Acesso negado!');
        } elseif (auth()->user()->hasRole('Passageiro')) {
            $passageiro = Passageiro::all()->where('users_id', auth()->user()->id)->first();
            if (!$passageiro) {
                return redirect()->back()->with('error', 'Você não é um passageiro registrado!');
            }
            $bilhetes = DB::table('bilhetes')
                ->join('viagens', 'bilhetes.viagens_id', 'viagens.id')
                ->leftJoin('passageiros', 'bilhetes.passageiros_id', 'passageiros.id')
                ->leftJoin('pessoas', 'passageiros.pessoas_id', 'pessoas.id')
                ->join('rotas', 'viagens.rotas_id', 'rotas.id')
                ->join('motoristas', 'viagens.motoristas_id', 'motoristas.id')
                ->join('autocarros', 'viagens.autocarros_id', 'autocarros.id')
                ->where('bilhetes.passageiros_id', $passageiro->id)
                ->get([
                    '*',
                    'bilhetes.id as id',
                ]);
        } else {
            $bilhetes = DB::table('bilhetes')
                ->join('viagens', 'bilhetes.viagens_id', 'viagens.id')
                ->leftJoin('passageiros', 'bilhetes.passageiros_id', 'passageiros.id')
                ->leftJoin('pessoas', 'passageiros.pessoas_id', 'pessoas.id')
                ->join('rotas', 'viagens.rotas_id', 'rotas.id')
                ->join('motoristas', 'viagens.motoristas_id', 'motoristas.id')
                ->join('autocarros', 'viagens.autocarros_id', 'autocarros.id')
                ->get([
                    '*',
                    'bilhetes.id as id',
                ]);
        }

        $bilhetes->map(function ($bilhete) {
            $bilhete->rota = $bilhete->origem . ' -> ' . $bilhete->destino;
            $bilhete->nome = $bilhete->passageiros_id ? primeiro_ultimo($bilhete->nome . ' ' . $bilhete->apelido) : 'Cliente Final';
            $bilhete->email = $bilhete->email ?? 'Não definido';
            $bilhete->telefone = $bilhete->telefone ?? 'Não definido';
            $bilhete->valor = formatar_moeda($bilhete->preco * $bilhete->qtd) ?? 0;
            $bilhete->numero_bilhete = 'TK-' . $bilhete->numero_bilhete . '/' . data_formatada($bilhete->data_emissao, 'Y') ?? 'N/A';
        });

        $viagens = DB::table('viagens')
            ->join('rotas', 'viagens.rotas_id', 'rotas.id')
            ->join('motoristas', 'viagens.motoristas_id', 'motoristas.id')
            ->join('pessoas', 'motoristas.pessoas_id', 'pessoas.id')
            ->join('autocarros', 'viagens.autocarros_id', 'autocarros.id')
            ->get([
                '*',
                'viagens.id as id',
            ]);

        $viagens->map(function ($viagen) {
            $viagen->rota = $viagen->origem . ' -> ' . $viagen->destino;
        });

        return view('bilhetes.index', [
            'bilhetes' => $bilhetes,
            'viagens' => $viagens,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $lastBilhete = Bilhete::orderBy('id', 'desc')->first();
        $request['numero_bilhete'] = $lastBilhete ? $lastBilhete->numero_bilhete + 1 : 1;
        $request['data_emissao'] = now();

        if (auth()->user()->hasRole('Passageiro')) {
            $passageiro = Passageiro::all()->where('users_id', auth()->user()->id)->first();
            $request['passageiros_id'] = $passageiro->id;
            $bilhete = Bilhete::updateOrCreate(
                ['id' => $request->id],
                $request->except(['id'])
            );
        } else {
            $bilhete = Bilhete::updateOrCreate(
                ['id' => $request->id],
                $request->except(['id', 'passageiros_id'])
            );
        }
        try {
            DB::beginTransaction();

            DB::commit();
            return redirect()->route('bilhetes.index')->with('success', 'Bilhete criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao criar bilhete: ' . $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Bilhete $bilhete)
    {
        return response()->json($bilhete);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bilhete $bihete)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bilhete $bihete)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bilhete $bihete)
    {
        //
    }
}
