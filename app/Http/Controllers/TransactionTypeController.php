<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionType;
use Illuminate\Support\Facades\Validator;

class TransactionTypeController extends Controller
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

        $transaction_type = TransactionType::when($search, function ($query) use ($search) {
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

        $transaction_type->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $transaction_type,
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:transaction_types|max:100',
            'description' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transaction_type = TransactionType::create([
            'name'        => $request->get('name'),
            'description' => $request->get('description'),
        ]);

        return response()->json([
            'message' => 'Transaction Type has been created successfully!',
            'data' => $transaction_type,
        ], 201);
    }

    public function show($id)
    {
        $transaction_type = TransactionType::find($id);
        if ($transaction_type) {
            return response()->json([
                'message' => 'Success!',
                'data' => $transaction_type
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $transaction_type = TransactionType::find($id);

        if ($transaction_type) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name'        => 'required|unique:transaction_types,name,' . $id . '|max:255',
                    'description' => 'required|max:255',
                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            $transaction_type = TransactionType::where('id', $id)->update($request->all());
            $data = TransactionType::where('id', $id)->first();

            return response()->json([
                'message' => 'Transaction Type has been updated successfully!',
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
            $transaction_type = TransactionType::where('id', $id)->first();
            if ($transaction_type) {
                $transaction_type->delete();
                return response()->json([
                    'message' => 'Transaction Type has been deleted successfully!',
                    'data'    => $transaction_type
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
