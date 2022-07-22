<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Engine;
use Illuminate\Support\Facades\Validator;

class EngineController extends Controller
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

        $engine = Engine::when($search, function ($query) use ($search) {
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

        $engine->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $engine
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:engine',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $engine = Engine::create([
            'name' => $request->get('name'),
        ]);

        return response()->json([
            'message' => 'Engine has been created successfully!',
            'data' => $engine,
        ], 201);
    }

    public function show($id)
    {
        $engine = Engine::find($id);
        if ($engine) {
            return response()->json([
                'message' => 'Success!',
                'data' => $engine
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $engine = Engine::find($id);

        if ($engine) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name'        => 'required|unique:engine,name,' . $id . '|max:100',

                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $engine = Engine::where('id', $id)->update($request->all());
            $data = Engine::where('id', $id)->first();

            return response()->json([
                'message' => 'Engine has been updated successfully!',
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
            $engine = Engine::where('id', $id)->first();
            if ($engine) {
                $engine->delete();
                return response()->json([
                    'message' => 'Engine has been deleted successfully!',
                    'data'    => $engine
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