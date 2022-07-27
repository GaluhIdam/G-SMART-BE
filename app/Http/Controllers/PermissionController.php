<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;


class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $search             = $request->get('search');
        $search_name        = $request->get('name');
        $search_guard_name = $request->get('guard_name');

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

        $permission = Permission::when($search, function ($query) use ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('guard_name', 'LIKE', "%{$search}%");
            });
        })->when($search_name, function ($query) use ($search_name) {
            $query->where('name', 'LIKE', "%{$search_name}%");
        })->when($search_guard_name, function ($query) use ($search_guard_name) {
            $query->where('guard_name', 'LIKE', "%{$search_guard_name}%");
        })->when(($order && $by), function ($query) use ($order, $by) {
            $query->orderBy($order, $by);
        })->paginate($paginate);

        $query_string = [
            'search' => $search,
            'order' => $order,
            'by' => $by,
        ];

        $permission->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $permission,
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permission|max:100',
            'guard_name' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $permission = Permission::create([
            'name'        => $request->get('name'),
            'guard_name' => $request->get('guard_name'),
        ]);

        return response()->json([
            'message' => 'Permission has been created successfully!',
            'data' => $permission,
        ], 201);
    }

    public function show($id)
    {
        $permission = Permission::find($id);
        if ($permission) {
            return response()->json([
                'message' => 'Success!',
                'data' => $permission
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);

        if ($permission) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name'        => 'required|unique:permission,name,' . $id . '|max:1000',
                    'guard_name' => 'required|max:100',
                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $permission = Permission::where('id', $id)->update($request->all());
            $data = Permission::where('id', $id)->first();

            return response()->json([
                'message' => 'Permission has been updated successfully!',
                'data' => $data,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $permission = Permission::where('id', $id)->first();
            if ($permission) {
                $permission->delete();
                return response()->json([
                    'message' => 'Permission has been deleted successfully!',
                    'data'    => $permission
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Data not found!',
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }
}
