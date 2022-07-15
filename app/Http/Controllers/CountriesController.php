<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountriesController extends Controller
{
    public function index(Request $request)
    {
        $search          = $request->get('search');
        $search_name     = $request->get('name');
        $search_region_id    = $request->get('region_id');

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

        $countries = Countries::when($search, function ($query) use ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('region_id', 'LIKE', "%{$search}%");
            });
        })->when($search_name, function ($query) use ($search_name) {
            $query->where('name', 'LIKE', "%{$search_name}%");
        })->when($search_region_id, function ($query) use ($search_region_id) {
            $query->where('region_id', 'LIKE', "%{$search_region_id}%");
        })->when(($order && $by), function ($query) use ($order, $by) {
            $query->orderBy($order, $by);
        })->paginate($paginate);

        $query_string = [
            'search' => $search,
            'order' => $order,
            'by' => $by,
        ];

        $countries->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $countries,
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:countries|max:100',
            'region_id' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $countries = Countries::create([
            'name'  => $request->get('name'),
            'region_id' => $request->get('region_id'),
        ]);

        return response()->json([
            'message' => 'Countries has been created successfully!',
            'data' => $countries,
        ], 201);
    }

    public function show($id)
    {
        $countries = Countries::find($id);
        if ($countries) {
            return response()->json([
                'message' => 'Success!',
                'data' => $countries
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $countries = Countries::find($id);

        if ($countries) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name'  => 'required|unique:countries,name,' . $id . '|max:100',
                    'region_id' => 'required|max:100',
                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $countries = Countries::where('id', $id)->update($request->all());
            $data = Countries::where('id', $id)->first();

            return response()->json([
                'message' => 'Countries has been updated successfully!',
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
            $countries = Countries::where('id', $id)->first();
            if ($countries) {
                $countries->delete();
                return response()->json([
                    'message' => 'Countries has been deleted successfully!',
                    'data'    => $countries
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
