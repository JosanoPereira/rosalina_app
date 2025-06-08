<?php

namespace App\Http\Controllers;

use App\Models\ModelHasPermission;
use App\Models\RoleHasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleHasPermissionController extends Controller
{

    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('role_has_permissions.index', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $role = Role::all()->find($request->roles_id);

        foreach ($request->permissions_id as $permission_id) {
            $permission = Permission::all()->find($permission_id);

            $mp = RoleHasPermission::all()
                ->where('permission_id', $permission_id)
                ->where('roles_id', $request->roles_id);

            if ($mp->isEmpty())
                $role->givePermissionTo($permission->name);

        }


        return redirect()->back()->with('sucesso', 'Data changed successfully!!');
    }

    public function delete(Request $request)
    {
        if (Gate::denies('delete'))
            return redirect()->back()->with('erro', 'Sorry, you are not allowed to delete');

        $role_permission = RoleHasPermission::all()->find($request->id);
        $role = Role::all()->find($role_permission->role_id);
        $permission = Permission::all()->find($role_permission->permission_id);

        $revoke = $role->revokePermissionTo($permission);
        //$permission->removeRole($role);
        if ($revoke) {
            return redirect()->back()->with('sucesso', 'Data successfully deleted!!');
        } else {
            return redirect()->back()->with('erro', 'Error while deleting');
        }
    }

    public function listar()
    {


        $json = DB::table('role_has_permissions')
            ->join('roles', 'roles.id', 'role_has_permissions.role_id')
            ->join('permissions', 'permissions.id', 'role_has_permissions.permission_id')
            ->select([
                '*',
                'roles.name as nome_role',
                'permissions.name as nome_permission',
                'role_has_permissions.id as id',
            ]);

        return DataTables::of($json)
            ->make(true);
    }
}
