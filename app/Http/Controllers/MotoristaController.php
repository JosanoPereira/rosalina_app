<?php

namespace App\Http\Controllers;

use App\Models\Genero;
use App\Models\Motorista;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MotoristaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $motoristas = DB::table('motoristas')
            ->join('pessoas', 'motoristas.pessoas_id', '=', 'pessoas.id')
            ->select('*', 'motoristas.id as id', 'pessoas.id as pessoas_id')
            ->get();
        $motoristas->map(function ($motorista) {
            $motorista->nome = $motorista->nome . ' ' . $motorista->apelido;
            $motorista->categoria = ucfirst($motorista->categoria);
            return $motorista;
        });
        $generos = Genero::all();
        return view('motoristas.index', [
            'motoristas' => $motoristas,
            'generos' => $generos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $generos = Genero::all();
        return view('motoristas.index', [
            'generos' => $generos,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $pessoa = Pessoa::updateOrCreate(
                ['id' => $request->pessoas_id],
                $request->only(['nome', 'apelido', 'bi', 'telefone', 'nascimento', 'generos_id'])
            );
            $request->merge(['pessoas_id' => $pessoa->id]);
            $dado = Motorista::updateOrCreate(
                ['id' => $request->id],
                $request->except(['id',])
            );
            DB::commit();
            return redirect()->route('motoristas.index')->with('success', 'Motorista criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao criar motorista: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Motorista $motorista)
    {
        $pessoa = Pessoa::find($motorista->pessoas_id);
        $motorista['nome'] = $pessoa->nome;
        $motorista['apelido'] = $pessoa->apelido;
        $motorista['bi'] = $pessoa->bi;
        $motorista['telefone'] = $pessoa->telefone;
        $motorista['nascimento'] = $pessoa->nascimento;
        $motorista['generos_id'] = $pessoa->generos_id;
        return response()->json($motorista);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Motorista $motorista)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Motorista $motorista)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Motorista $motorista)
    {
        //
    }
}
