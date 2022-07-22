<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Components;
use Illuminate\Support\Facades\Validator;

class ComponentsController extends Controller
{
    public function index(Request $request)
    {
        $search             = $request->get('search');
        $search_name        = $request->get('name');

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

        $components = Components::when($search, function ($query) use ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        })->when($search_name, function ($query) use ($search_name) {
            $query->where('name', 'LIKE', "%{$search_name}%");
        })->when(($order && $by), function ($query) use ($order, $by) {
            $query->orderBy($order, $by);
        })->paginate($paginate);

        $query_string = [
            'search' => $search,
            'order' => $order,
            'by' => $by,
        ];

        $components->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $components
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:components',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $components = Components::create([
            'name' => $request->get('name'),
        ]);

        return response()->json([
            'message' => 'Component has been created successfully!',
            'data' => $components,
        ], 201);
    }

    public function show($id)
    {
        $components = Components::find($id);
        if ($components) {
            return response()->json([
                'message' => 'Success!',
                'data' => $components
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $components = Components::find($id);

        if ($components) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name'        => 'required|unique:components,name,' . $id . '|max:100',

                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $components = Components::where('id', $id)->update($request->all());
            $data = Components::where('id', $id)->first();

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
            $components = Components::where('id', $id)->first();
            if ($components) {
                $components->delete();
                return response()->json([
                    'message' => 'Component has been deleted successfully!',
                    'data'    => $components
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