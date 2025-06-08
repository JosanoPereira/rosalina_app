<?php

namespace App\Http\Controllers;

use App\Models\Bilhete;
use Illuminate\Http\Request;

class BilheteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bilhetes = Bilhete::all();
        return view('bilhetes.index', [
            'bilhetes' => $bilhetes,
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
        $request->validate([
            'numero_bilhete' => 'required|unique:bilhetes,numero_bilhete',
            'data_emissao' => 'required|date',
            'data_validade' => 'required|date|after:data_emissao',
            'passageiros_id' => 'required|exists:passageiros,id',
        ]);

        Bilhete::updateOrCrete(
            ['id' => $request->id],
            [
                'numero_bilhete' => $request->numero_bilhete,
                'data_emissao' => $request->data_emissao,
                'data_validade' => $request->data_validade,
                'passageiros_id' => $request->passageiros_id
            ]
        );

        return redirect()->route('bilhetes.index')->with('success', 'Bilhete criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bilhete $bihete)
    {
        //
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
