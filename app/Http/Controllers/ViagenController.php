<?php

namespace App\Http\Controllers;

use App\Models\Autocarro;
use App\Models\Motorista;
use App\Models\Rota;
use App\Models\Viagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViagenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $viagens = DB::table('viagens')
            ->join('rotas', 'viagens.rotas_id', 'rotas.id')
            ->join('motoristas', 'viagens.motoristas_id', 'motoristas.id')
            ->join('pessoas', 'motoristas.pessoas_id', 'pessoas.id')
            ->join('autocarros', 'viagens.autocarros_id', 'autocarros.id')
            ->select(
                '*',
                'viagens.id as id',
            )
            ->get();
        $viagens->map(function ($viagem) {
            $viagem->nome = $viagem->nome . ' ' . $viagem->apelido;
            $viagem->rota = $viagem->origem . ' - ' . $viagem->destino;
            $viagem->autocarro = $viagem->marca . ' - ' . $viagem->modelo;
            return $viagem;
        });
        $rotas = Rota::all();
        $motoristas = DB::table('motoristas')
            ->join('pessoas', 'motoristas.pessoas_id', 'pessoas.id')
            ->select('*', 'motoristas.id as id', 'pessoas.nome as nome')
            ->get();

        $motoristas->map(function ($motorista) {
            $motorista->nome = $motorista->nome . ' ' . $motorista->apelido;
            return $motorista;
        });

        $autocarros = Autocarro::all();
        return view('viagens.index', [
            'viagens' => $viagens,
            'rotas' => $rotas,
            'motoristas' => $motoristas,
            'autocarros' => $autocarros,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $dado = Viagen::updateOrCreate(
                ['id' => $request->id],
                $request->except(['id'])
            );
            DB::commit();
            return redirect()->route('viagens.index')->with('success', 'Viagem criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('viagens.index')->with('error', 'Erro ao criar a viagem: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Viagen $viagen)
    {
        return response()->json($viagen);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Viagen $viagen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Viagen $viagen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Viagen $viagen)
    {
        //
    }
}
