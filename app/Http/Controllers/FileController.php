<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
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

        $file = File::with('sales_requirement_id')->when($search, function ($query) use ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('sales_requirment_id', 'LIKE', "%$search%")
                    ->orWhere('path', 'LIKE', "%$search%");
            });
        })->when(($order && $by), function ($query) use ($order, $by) {
            $query->orderBy($order, $by);
        })->paginate($paginate);

        $query_string = [
            'search' => $search,
            'order'  => $order,
            'by'     => $by,
        ];

        $file->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data'    => $file,
        ], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'sales_requirment_id' => 'required|numeric',
            'path' => 'required',
        ]);

        $file = File::create($request->all());

        return response()->json([
            'message' => 'File has been created successfully!',
            'data' => $file,
        ], 201);
    }

    public function show($id)
    {
        if ($file = File::find($id)) {
            return response()->json([
                'message' => 'Success!',
                'data'    => $file
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if ($file = File::find($id)) {
            $request->validate([
                'sales_requirment_id' => 'required|unique:files,sales_requirment_id,' . $id . '',
                'path'                => 'required',
            ]);

            $file->update($request->all());

            return response()->json([
                'message' => 'File has been updated successfully!',
                'data'    => $file,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function destroy($id)
    {
        if ($file = File::find($id)) {
            $file->delete();
            return response()->json([
                'message' => 'File has been deleted successfully!',
                'data'    => $file
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }
}
