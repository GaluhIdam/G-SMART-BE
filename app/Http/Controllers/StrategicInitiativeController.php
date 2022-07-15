<?php

namespace App\Http\Controllers;

use App\Models\StrategicInitiatives;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StrategicInitiativeController extends Controller
{
    public function index(Request $request)
    {
        $search             = $request->get('search');
        $search_name        = $request->get('name');
        $search_description = $request->get('description');

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

        $strategic_initiative = StrategicInitiatives::when($search, function ($query) use ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        })->when($search_name, function ($query) use ($search_name) {
            $query->where('name', 'LIKE', "%{$search_name}%");
        })->when($search_description, function ($query) use ($search_description) {
            $query->where('description', 'LIKE', "%{$search_description}%");
        })->when(($order && $by), function ($query) use ($order, $by) {
            $query->orderBy($order, $by);
        })->paginate($paginate);

        $query_string = [
            'search' => $search,
            'order' => $order,
            'by' => $by,
        ];

        $strategic_initiative->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $strategic_initiative,
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:strategic_initiatives|max:100',
                'description' => 'required|max:100',
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $strategic_initiative = StrategicInitiatives::create([
            'name'        => $request->get('name'),
            'description' => $request->get('description'),
        ]);

        return response()->json([
            'message' => 'Strategic Initiative has been created successfully!',
            'data' => $strategic_initiative,
        ], 201);
    }

    public function show($id)
    {
        $strategic_initiative = StrategicInitiatives::find($id);
        if ($strategic_initiative) {
            return response()->json([
                'message' => 'Success!',
                'data' => $strategic_initiative
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $strategic_initiative = StrategicInitiatives::find($id);

        if ($strategic_initiative) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name'        => 'required|unique:strategic_initiatives,name,' . $id . '|max:100',
                    'description' => 'required|max:100',
                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $strategic_initiative = StrategicInitiatives::where('id', $id)->update($request->all());
            $data = StrategicInitiatives::where('id', $id)->first();

            return response()->json([
                'message' => 'Strategic Initiative has been updated successfully!',
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
            $strategic_initiative = StrategicInitiatives::where('id', $id)->first();
            if ($strategic_initiative) {
                $strategic_initiative->delete();
                return response()->json([
                    'message' => 'Strategic Initiative has been deleted successfully!',
                    'data'    => $strategic_initiative
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
