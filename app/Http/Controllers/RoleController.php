<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return view('roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dados = [
            'name' => $request->name,
            'guard_name' => 'web',
        ];
        $save = Role::create($dados);
        if ($save) {
            return redirect()->back()->with('sucesso', 'Data changed successfully!!');
        } else {
            return redirect()->back()->with('erro', 'Error saving');
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $json = DB::table('roles')
            ->where('id', $request->id)
            ->get();
        echo json_encode($json->all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
    }

    public function delete(Request $request)
    {
        if (Gate::denies('delete'))
            return redirect()->back()->with('erro', 'Sorry, you are not allowed to delete');

        if (Role::all()->find($request->id)->delete()) {
            return redirect()->back()->with('sucesso', 'Data successfully deleted!!');
        } else {
            return redirect()->back()->with('erro', 'Error while deleting');
        }
    }

    //URL
    public function listar()
    {
        $json = Role::all();
        echo json_encode(array('data' => $json));
    }
}
