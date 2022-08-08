<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleHasPermission;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        if ($request->get('order') && $request->get('by')) {
            $order = $request->get('order');
            $by = $request->get('by');
        } else {
            $order = 'id';
            $by = 'desc';
        }

        if ($request->get('paginate')) {
            $paginate = $request->get('paginate');
        } else {
            $paginate = 10;
        }

        $role = Role::with('permissions')->when($search, function ($query) use ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('name', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%");
            });
        })->when(($order && $by), function ($query) use ($order, $by) {
            $query->orderBy($order, $by);
        })->paginate($paginate);

        $query_string = [
            'search' => $search,
            'order' => $order,
            'by' => $by,
        ];

        $role->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $role,
        ], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|max:255',
            'description' => 'required|max:255',
            'permission_id' => 'required',
        ]);

        DB::beginTransaction();
        $role = Role::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'guard_name' => 'web',
        ]);

        for ($i = 0; $i < count($request->get('permission_id')); $i++) {
            $permission_id[] = [
                RoleHasPermission::create([
                    'permission_id' => $request->get('permission_id')[$i],
                    'role_id' => $role->id
                ]),
            ];
        }
        DB::commit();

        return response()->json([
            'message' => 'Role has been created successfully!',
            'data' => [$role, $permission_id],
        ], 201);
    }

    public function show($id)
    {
        if ($role = Role::with('permissions')->find($id)) {
            return response()->json([
                'message' => 'Success!',
                'data' => $role
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if ($role = Role::with('permissions')->find($id)) {
            $request->validate([
                'name' => 'required|unique:roles,name,' . $id . '|max:255',
                'description' => 'required|max:255',
                // 'permission_id' => 'required',
            ]);

            if (count($role->permissions) < count($request->get('permission_id'))) {
                for ($i = 0; $i < count($request->get('permission_id')); $i++) {
                    if (RoleHasPermission::where('permission_id', $request->get('permission_id'))->where('role_id', $role->id)) {
                        $permission_id[] = [
                            RoleHasPermission::create([
                                'permission_id' => $request->get('permission_id')[$i],
                                'role_id' => $role->id
                            ]),
                        ];
                    } else {
                        return 'sama s';
                    }
                }
                return 'sama';
            } else {
                return 'beda';
            }
            // return $role->permissions;
            die;
            DB::beginTransaction();
            $role->update();

            DB::commit();

            return response()->json([
                'message' => 'Role has been updated successfully!',
                'data' => $role,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function destroy($id)
    {
        if ($role = Role::find($id)) {
            RoleHasPermission::where('role_id', $role->id)->delete();
            $role->delete();
            return response()->json([
                'message' => 'Role has been deleted successfully!',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }
}
