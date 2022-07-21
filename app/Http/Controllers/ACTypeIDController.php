<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ACTypeID;
use Illuminate\Support\Facades\Validator;

class ACTypeIDController extends Controller
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

        $ac_type_id = ACTypeID::when($search, function ($query) use ($search) {
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

        $ac_type_id->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $ac_type_id
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:ac_type_id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $ac_type_id = ACTypeID::create([
            'name' => $request->get('name'),
        ]);

        return response()->json([
            'message' => 'Aircraft Type has been created successfully!',
            'data' => $ac_type_id,
        ], 201);
    }

    public function show($id)
    {
        $ac_type_id = ACTypeID::find($id);
        if ($ac_type_id) {
            return response()->json([
                'message' => 'Success!',
                'data' => $ac_type_id
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $ac_type_id = ACTypeID::find($id);

        if ($ac_type_id) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name'        => 'required|unique:ac_type_id,name,' . $id . '|max:100',

                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $ac_type_id = ACTypeID::where('id', $id)->update($request->all());
            $data = ACTypeID::where('id', $id)->first();

            return response()->json([
                'message' => 'Aircraft Type has been updated successfully!',
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
            $ac_type_id = ACTypeID::where('id', $id)->first();
            if ($ac_type_id) {
                $ac_type_id->delete();
                return response()->json([
                    'message' => 'Aircraft Type has been deleted successfully!',
                    'data'    => $ac_type_id
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