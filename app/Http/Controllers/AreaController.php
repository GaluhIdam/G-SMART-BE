<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
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

        $area = Area::when($search, function ($query) use ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('scope', 'LIKE', "%{$search}%");
            });
        })->when(($order && $by), function ($query) use ($order, $by) {
            $query->orderBy($order, $by);
        })->paginate($paginate);

        $query_string = [
            'search' => $search,
            'order' => $order,
            'by' => $by,
        ];

        $area->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $area,
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:areas|max:255',
            'scope' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $area = Area::create([
            'name'  => $request->get('name'),
            'scope' => $request->get('scope'),
        ]);

        return response()->json([
            'message' => 'Area has been created successfully!',
            'data' => $area,
        ], 201);
    }

    public function show($id)
    {
        $area = Area::find($id);
        if ($area) {
            return response()->json([
                'message' => 'Success!',
                'data' => $area
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $area = Area::find($id);

        if ($area) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name'  => 'required|unique:areas,name,' . $id . '|max:2550',
                    'scope' => 'required|max:255',
                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $area = Area::where('id', $id)->update($request->all());
            $data = Area::where('id', $id)->first();

            return response()->json([
                'message' => 'Area has been updated successfully!',
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
            $area = Area::where('id', $id)->first();
            if ($area) {
                $area->delete();
                return response()->json([
                    'message' => 'Area has been deleted successfully!',
                    'data'    => $area
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
