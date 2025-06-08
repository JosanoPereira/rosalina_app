<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PermissionRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PermissionRoleController extends Controller
{
    public function index()
    {
        /*$json = User::with('roles')->get();
        dd($json);*/
        $permissions = DB::table('permissions')->get()->all();
        $roles = DB::table('roles')->get()->all();
        return view('permission_role.index', [
            'permissions'=>$permissions,
            'roles'=>$roles,
        ]);
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
            'permission_id' => $request->permission_id,
            'role_id' => $request->role_id
        ];
        $save = DB::table('permission_role')->updateOrInsert(['id' => $request->id], $dados);
        if ($save) {
            return redirect()->back()->with('sucesso', 'Data changed successfully!!');
        } else {
            return redirect()->back()->with('erro', 'Error saving');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\PermissionRole $permission_role
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $json = DB::table('permission_role')
            ->join('permissions', 'permissions.id', 'permission_role.permission_id')
            ->join('roles', 'roles.id', 'permission_role.role_id')
            ->where('permission_role.id', $request->id)
            ->get(['*', 'permission_role.id as id_p', 'permissions.label as label_p', 'roles.label as label_r']);
        echo json_encode($json->all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\PermissionRole $permission_role
     * @return \Illuminate\Http\Response
     */
    public function edit(PermissionRole $permission_role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PermissionRole $permission_role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PermissionRole $permission_role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\PermissionRole $permission_role
     * @return \Illuminate\Http\Response
     */
    public function destroy(PermissionRole $permission_role)
    {
        //
    }

    public function delete(Request $request)
    {
        if (Gate::denies('delete'))
           return redirect()->back()->with('erro', 'Sorry, you are not allowed to delete');

        if (DB::table('permission_role')->where('id', $request->id)->delete()) {
            DB::statement("ALTER TABLE permission_role AUTO_INCREMENT =  $request->id");
            return redirect()->back()->with('sucesso', 'Data successfully deleted!!');
        } else {
            return redirect()->back()->with('erro', 'Error while deleting');
        }
    }

    //URL
    public function listar()
    {
//        $json = User::with('roles')->get()->all();
        $json = DB::table('permission_role')
            ->join('permissions', 'permissions.id', 'permission_role.permission_id')
            ->join('roles', 'roles.id', 'permission_role.role_id')
            ->get(['*', 'permission_role.id as id_p', 'permissions.label as label_p', 'roles.label as label_r']);
        echo json_encode(array('data' => $json));
    }
}
