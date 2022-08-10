<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\AMSCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Database\Eloquent\Builder;

class CustomerController extends Controller
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

        $customer = Customer::with('country_id.region_id')->when($search, function ($query) use ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('name', 'LIKE', "%$search%")
                    ->orWhere('code', 'LIKE', "%$search%");
            });
        })->when(($order && $by), function ($query) use ($order, $by) {
            $query->orderBy($order, $by);
        })->paginate($paginate);

        $query_string = [
            'search' => $search,
            'order' => $order,
            'by' => $by,
        ];

        $customer->appends($query_string);

        return response()->json([
            'message' => 'Success!',
            'data' => $customer
        ], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'country_id' => 'required',
            'region_id' => 'required',
            'ams_id' => 'required',
            'area_id' => 'required',
        ]);
        DB::beginTransaction();
        $customer = Customer::create($request->all());
        foreach ($request->get('ams_id') as $value) {
            return $value;
            AMSCustomer::create([
                'customer_id' => $customer->id,
                'ams_id' => $value,
                'area_id' => $value,
            ]);
        }
        die;
        DB::commit();

        return response()->json([
            'message' => 'Customer has been created successfully!',
            // 'data' => $customer,
        ], 201);
    }

    public function show($id)
    {
        if ($customer = Customer::find($id)) {
            return response()->json([
                'message' => 'Success!',
                'data' => $customer
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if ($customer = Customer::find($id)) {
            $request->validate([
                'name'        => 'required|max:255',
                'code'        => 'required|max:255',
            ]);

            $customer->update($request->all());

            return response()->json([
                'message' => 'Customer has been updated successfully!',
                'data' => $customer,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function destroy($id)
    {
        if ($customer = Customer::find($id)) {
            $customer->delete();
            return response()->json([
                'message' => 'Customer has been deleted successfully!',
                'data'    => $customer
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }
}
