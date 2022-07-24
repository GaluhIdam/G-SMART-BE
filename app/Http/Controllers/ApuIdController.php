<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApuId;
use Illuminate\Support\Facades\Validator;

class ApuIdController extends Controller
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

        $apu_ids = ApuId::when($search, function ($query) use ($search) {
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

        $apu_ids->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $apu_ids
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:apu_ids',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $apu_ids = ApuId::create([
            'name' => $request->get('name'),
        ]);

        return response()->json([
            'message' => 'Component has been created successfully!',
            'data' => $apu_ids,
        ], 201);
    }

    public function show($id)
    {
        $apu_ids = ApuId::find($id);
        if ($apu_ids) {
            return response()->json([
                'message' => 'Success!',
                'data' => $apu_ids
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $apu_ids = ApuId::find($id);

        if ($apu_ids) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name'        => 'required|unique:apu_ids,name,' . $id . '|max:100',

                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $apu_ids = ApuId::where('id', $id)->update($request->all());
            $data = ApuId::where('id', $id)->first();

            return response()->json([
                'message' => 'apu_id has been updated successfully!',
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
            $apu_ids = ApuId::where('id', $id)->first();
            if ($apu_ids) {
                $apu_ids->delete();
                return response()->json([
                    'message' => 'apu_id has been deleted successfully!',
                    'data'    => $apu_ids
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
