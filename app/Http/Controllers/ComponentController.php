<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Component;
use Illuminate\Support\Facades\Validator;

class ComponentController extends Controller
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

        $component = Component::when($search, function ($query) use ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('name', 'LIKE', "%{$search}%");
            });
        })->when(($order && $by), function ($query) use ($order, $by) {
            $query->orderBy($order, $by);
        })->paginate($paginate);

        $query_string = [
            'search' => $search,
            'order' => $order,
            'by' => $by,
        ];

        $component->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $component
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:component_id|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $component = Component::create([
            'name' => $request->get('name'),
        ]);

        return response()->json([
            'message' => 'Component has been created successfully!',
            'data' => $component,
        ], 201);
    }

    public function show($id)
    {
        $component = Component::find($id);
        if ($component) {
            return response()->json([
                'message' => 'Success!',
                'data' => $component
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $component = Component::find($id);

        if ($component) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|unique:component_id,name,' . $id . '|max:255',
                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $component = Component::where('id', $id)->update($request->all());
            $data = Component::where('id', $id)->first();

            return response()->json([
                'message' => 'Component has been updated successfully!',
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
            $component = Component::where('id', $id)->first();
            if ($component) {
                $component->delete();
                return response()->json([
                    'message' => 'Component has been deleted successfully!',
                    'data'    => $component
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
