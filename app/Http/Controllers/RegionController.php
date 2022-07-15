<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        $search          = $request->get('search');
        $search_name     = $request->get('name');
        $search_area_id    = $request->get('area_id');

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

        $region = Region::when($search, function ($query) use ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('area_id', 'LIKE', "%{$search}%");
            });
        })->when($search_name, function ($query) use ($search_name) {
            $query->where('name', 'LIKE', "%{$search_name}%");
        })->when($search_area_id, function ($query) use ($search_area_id) {
            $query->where('area_id', 'LIKE', "%{$search_area_id}%");
        })->when(($order && $by), function ($query) use ($order, $by) {
            $query->orderBy($order, $by);
        })->paginate($paginate);

        $query_string = [
            'search' => $search,
            'order' => $order,
            'by' => $by,
        ];

        $region->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $region,
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:areas|max:100',
            'area_id' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $region = Region::create([
            'name'  => $request->get('name'),
            'area_id' => $request->get('area_id'),
        ]);

        return response()->json([
            'message' => 'Region has been created successfully!',
            'data' => $region,
        ], 201);
    }

    public function show($id)
    {
        $region = Region::find($id);
        if ($region) {
            return response()->json([
                'message' => 'Success!',
                'data' => $region
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $region = Region::find($id);

        if ($region) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name'    => 'required|unique:regions,name,' . $id . '|max:1000',
                    'area_id' => 'required|max:100',
                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $region = Region::where('id', $id)->update($request->all());
            $data = Region::where('id', $id)->first();

            return response()->json([
                'message' => 'Region has been updated successfully!',
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
            $region = Region::where('id', $id)->first();
            if ($region) {
                $region->delete();
                return response()->json([
                    'message' => 'Region has been deleted successfully!',
                    'data'    => $region
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
