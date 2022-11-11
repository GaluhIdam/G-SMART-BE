<?php

namespace App\Http\Controllers;

use App\Models\AMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AMSController extends Controller
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
            $paginate = AMS::all()->count();
        }

        $ams = AMS::with('user')
                ->search($search)
                ->sort($order, $by)
                ->paginate($paginate)
                ->withQueryString();

        return response()->json([
            'message' => 'Success!',
            'data' => $ams,
        ], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'initial' => 'required|unique:ams|max:255',
            'user_id' => 'required',
        ]);

        $ams = AMS::create($request->all());

        return response()->json([
            'message' => 'AMS has been created successfully!',
            'data' => $ams,
        ], 201);
    }

    public function show($id)
    {
        $ams =  AMS::whereHas('amsCustomers', function ($query) use ($id) {
            $query->where('customer_id', $id);
            })->get();
            
            if($ams){
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
        if ($ams = AMS::find($id)) {
            $request->validate([
                'initial' => 'required|unique:ams,initial,' . $id . '|max:255',
                'user_id' => 'required',
            ]);

            $ams->update($request->all());

            return response()->json([
                'message' => 'AMS has been updated successfully!',
                'data' => $ams,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function destroy($id)
    {
        if ($ams = AMS::find($id)) {
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
    }
}
