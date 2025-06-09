<?php

namespace App\Http\Controllers;

use App\Models\Parada;
use Illuminate\Http\Request;

class ParadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paradas = Parada::all();
        return view('paradas.index', [
            'paradas' => $paradas,
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
        $dado = Parada::updateOrCreate(
            ['id' => $request->id],
            $request->except(['id'])
        );

        return redirect()->route('paradas.index')->with('success', 'Parada criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Parada $parada)
    {
        return response()->json($parada);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parada $parada)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Parada $parada)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parada $parada)
    {
        //
    }
}
