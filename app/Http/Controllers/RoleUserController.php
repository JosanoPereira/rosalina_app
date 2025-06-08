<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class RoleUserController extends Controller
{
    public function index()
    {
        /*$json = User::with('roles')->get();
        dd($json);*/
        $users = DB::table('users')->get()->all();
        $roles = DB::table('roles')->get()->all();
        return view('role_user.index', [
            'users'=>$users,
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
            'user_id' => $request->user_id,
            'role_id' => $request->role_id
        ];
        $save = DB::table('model_has_roles')->updateOrInsert(['id' => $request->id], $dados);
        if ($save) {
            return redirect()->back()->with('sucesso', 'Data changed successfully!!');
        } else {
            return redirect()->back()->with('erro', 'Error saving');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\RoleUser $role_user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $json = DB::table('model_has_roles')
            ->join('roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.user_id')
            ->where('model_has_roles.id', $request->id)
            ->get(['*', 'model_has_roles.id as id_p']);
        echo json_encode($json->all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\RoleUser $role_user
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
     * @param \App\Models\RoleUser $role_user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\RoleUser $role_user
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

        if (DB::table('model_has_roles')->where('id', $request->id)->delete()) {
            DB::statement("ALTER TABLE model_has_roles AUTO_INCREMENT =  $request->id");
            return redirect()->back()->with('sucesso', 'Data successfully deleted!!');
        } else {
            return redirect()->back()->with('erro', 'Error while deleting');
        }
    }

    //URL
    public function listar()
    {
//        $json = User::with('roles')->get()->all();
        $json = DB::table('model_has_roles')
            ->join('roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.user_id')
            ->get(['*', 'model_has_roles.id as id_p']);
        echo json_encode(array('data' => $json));
    }
}
