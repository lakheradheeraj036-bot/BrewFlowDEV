<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($p) {
            return explode('.', $p->name)[0];
        });

        return view('super-admin.roles.index', compact('roles', 'permissions'));
    }

    public function togglePermission(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_name' => 'required|string',
        ]);

        $role = Role::findOrFail($request->role_id);

        if ($role->name === 'super_admin') {
            return response()->json(['error' => 'Super Admin permissions cannot be modified.'], 403);
        }

        if ($role->hasPermissionTo($request->permission_name)) {
            $role->revokePermissionTo($request->permission_name);
            $hasPermission = false;
        } else {
            $role->givePermissionTo($request->permission_name);
            $hasPermission = true;
        }

        return response()->json(['success' => true, 'hasPermission' => $hasPermission]);
    }
}
