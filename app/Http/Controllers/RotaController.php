<?php

namespace App\Http\Controllers;

use App\Models\Rota;
use Illuminate\Http\Request;

class RotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rotas = Rota::all();
        return view('rotas.index', [
            'rotas' => $rotas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rotas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'origem' => 'required',
            'destino' => 'required',
            'distancia' => 'required|numeric',
            'tempo_estimado' => 'required',
            'waypoints' => 'nullable|json',
        ]);
        Rota::create($data);
        return redirect()->route('rotas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rota $rota)
    {
        return view('rotas.show', [
            'rota' => $rota,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rota $rota)
    {
        return view('rotas.edit', [
            'rota' => $rota,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rota $rota)
    {
        $data = $request->validate([
            'origem' => 'required',
            'destino' => 'required',
            'distancia' => 'required|numeric',
            'tempo_estimado' => 'required',
            'waypoints' => 'nullable|json',
        ]);
        $rota->update($data);
        return redirect()->route('rotas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rota $rota)
    {
        $rota->delete();
        return redirect()->route('rotas.index');
    }
}
