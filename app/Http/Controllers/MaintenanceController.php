<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
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

        $maintenance = Maintenance::when($search, function ($query) use ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        })->when(($order && $by), function ($query) use ($order, $by) {
            $query->orderBy($order, $by);
        })->paginate($paginate);

        $query_string = [
            'search' => $search,
            'order' => $order,
            'by' => $by,
        ];

        $maintenance->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $maintenance,
        ], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:maintenances|max:255',
            'description' => 'required|max:255',
        ]);

        $maintenance = Maintenance::create($request->all());

        return response()->json([
            'message' => 'Maintenance has been created successfully!',
            'data' => $maintenance,
        ], 201);
    }

    public function show($id)
    {
        if ($maintenance = Maintenance::find($id)) {
            return response()->json([
                'message' => 'Success!',
                'data' => $maintenance
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if ($maintenance = Maintenance::find($id)) {
            $request->all([
                'name'        => 'required|unique:maintenances,name,' . $id . '|max:255',
                'description' => 'required|max:255',
            ]);

            $maintenance->update($request->all());

            return response()->json([
                'message' => 'Maintenance has been updated successfully!',
                'data' => $maintenance,
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
            $maintenance = Maintenance::where('id', $id)->first();
            if ($maintenance) {
                $maintenance->delete();
                return response()->json([
                    'message' => 'Maintenance has been deleted successfully!',
                    'data'    => $maintenance
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
