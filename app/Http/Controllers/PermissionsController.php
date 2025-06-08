<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PermissionsController extends Controller
{
    public function index()
    {
        return view('permissions.index');
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
            'label' => $request->label
        ];
        $save = DB::table('permissions')->updateOrInsert(['id' => $request->id], $dados);
        if ($save) {
            return redirect()->back()->with('sucesso', 'Data changed successfully!!');
        } else {
            return redirect()->back()->with('erro', 'Error saving');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $json = DB::table('permissions')
            ->where('id', $request->id)
            ->get();
        echo json_encode($json->all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        //
    }

    public function delete(Request $request)
    {
        if (Gate::denies('delete'))
           return redirect()->back()->with('erro', 'Sorry, you are not allowed to delete');

        if (DB::table('permissions')->where('id', $request->id)->delete()) {
            DB::statement("ALTER TABLE permissions AUTO_INCREMENT =  $request->id");
            return redirect()->back()->with('sucesso', 'Data successfully deleted!!');
        } else {
            return redirect()->back()->with('erro', 'Error while deleting');
        }
    }

    //URL
    public function listar()
    {
        $json = Permission::all();
        echo json_encode(array('data' => $json));
    }
}
