<?php

namespace App\Http\Controllers;

use App\Models\AMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AMSController extends Controller
{
    public function index(Request $request)
    {
        $search             = $request->get('search');
        $search_initial     = $request->get('initial');
        $search_user_id     = $request->get('user_id');

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

        $ams = AMS::when($search, function ($query) use ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('initial', 'LIKE', "%{$search}%")
                    ->orWhere('user_id', 'LIKE', "%{$search}%");
            });
        })->when($search_initial, function ($query) use ($search_initial) {
            $query->where('initial', 'LIKE', "%{$search_initial}%");
        })->when($search_user_id, function ($query) use ($search_user_id) {
            $query->where('user_id', 'LIKE', "%{$search_user_id}%");
        })->when(($order && $by), function ($query) use ($order, $by) {
            $query->orderBy($order, $by);
        })->paginate($paginate);

        $query_string = [
            'search' => $search,
            'order' => $order,
            'by' => $by,
        ];

        $ams->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $ams,
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'initial' => 'required|unique:ams|max:100',
            'user_id' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $ams = AMS::create([
            'initial'        => $request->get('initial'),
            'user_id'        => $request->get('user_id'),
        ]);

        return response()->json([
            'message' => 'AMS has been created successfully!',
            'data' => $ams,
        ], 201);
    }

    public function show($id)
    {
        $ams = AMS::find($id);
        if ($ams) {
            return response()->json([
                'message' => 'Success!',
                'data' => $ams
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $ams = AMS::find($id);

        if ($ams) {
            $validator = Validator::make(
                $request->all(),
                [
                    'initial' => 'required|unique:ams,name,' . $id . '|max:1000',
                    'user_id' => 'required|max:100',
                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $ams = AMS::where('id', $id)->update($request->all());
            $data = AMS::where('id', $id)->first();

            return response()->json([
                'message' => 'AMS has been updated successfully!',
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
            $ams = AMS::where('id', $id)->first();
            if ($ams) {
                $ams->delete();
                return response()->json([
                    'message' => 'AMS has been deleted successfully!',
                    'data'    => $ams
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
