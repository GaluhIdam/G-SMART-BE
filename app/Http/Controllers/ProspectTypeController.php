<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProspectType;

class ProspectTypeController extends Controller
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

        $prospect_type = ProspectType::when($search, function ($query) use ($search) {
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

        $prospect_type->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $prospect_type
        ], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:prospect_types|max:255',
            'description' => 'required|max:255',
        ]);

        $prospect_type = ProspectType::create($request->all());

        return response()->json([
            'message' => 'Prospect Type has been created successfully!',
            'data' => $prospect_type,
        ], 201);
    }

    public function show($id)
    {
        if ($prospect_type = ProspectType::find($id)) {
            return response()->json([
                'message' => 'Success!',
                'data' => $prospect_type
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if ($prospect_type = ProspectType::find($id)) {
            $request->validate([
                'name'        => 'required|unique:prospect_types,name,' . $id . '|max:255',
                'description' => 'required|max:255',
            ]);

            $prospect_type->update($request->all());

            return response()->json([
                'message' => 'Prospect Type has been updated successfully!',
                'data' => $prospect_type,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function destroy($id)
    {
        if ($prospect_type = ProspectType::find($id)) {
            $prospect_type->delete();
            return response()->json([
                'message' => 'Prospect Type has been deleted successfully!',
                'data'    => $prospect_type
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }
}
