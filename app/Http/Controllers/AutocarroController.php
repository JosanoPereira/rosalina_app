<?php

namespace App\Http\Controllers;

use App\Models\Autocarro;
use Illuminate\Http\Request;

class AutocarroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $autocarros = Autocarro::all();
        return view('autocarros.index', [
            'autocarros' => $autocarros,
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

        $dado = Autocarro::updateOrCreate(
            ['id' => $request->id],
            $request->except(['id',])
        );

        return redirect()->route('autocarros.index')->with('success', 'Autocarro criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Autocarro $autocarro)
    {
        return response()->json($autocarro);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Autocarro $autocarro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Autocarro $autocarro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Autocarro $autocarro)
    {
        //
    }
}
