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
            $paginate = Customer::all()->count();
        }

        $customer = DB::connection('mysql')
                    ->table('customers')
                    ->select('customers.*',
                            'countries.name AS country',
                            'regions.name AS region')
                    ->join('countries', 'customers.country_id', '=', 'countries.id')
                    ->join('regions', 'countries.region_id', '=', 'regions.id')
                    ->where('customers.code', 'LIKE', "%{$search}%")
                    ->orWhere('customers.name', 'LIKE', "%{$search}%")
                    ->orWhere('countries.name', 'LIKE', "%{$search}%")
                    ->orWhere('regions.name', 'LIKE', "%{$search}%")
                    ->orderBy($order, $by)
                    ->paginate($paginate)
                    ->appends([
                        'search' => $search,
                        'order' => $order,
                        'by' => $by,
                    ]);

        return response()->json([
            'message' => 'Success!',
            'data' => $customer
        ], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'code' => 'required|max:255',
            'country_id' => 'required',
            'region_id' => 'required',
        ]);

        DB::beginTransaction();
        $customer = Customer::create($request->all());

        foreach ($request->get('area_ams') as $value) {
            AMSCustomer::create([
                'customer_id' => $customer->id,
                'ams_id' => $value['ams']['id'],
                'area_id' => $value['area']['id'],
            ]);
        }
        DB::commit();

        return response()->json([
            'message' => 'Customer has been created successfully!',
            'data' => $customer,
        ], 201);
    }

    public function show(Request $request)
    {
        $customer = Customer::with('country.region')->with('amsCustomers.area')->with('amsCustomers.ams.user')->where(
            'id',
            $request->id
        )->get();

        if ($customer->isNotEmpty()) {
            return response()->json([
                'message' => 'Success!',
                'data' => $customer->first()
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if ($customer = Customer::with('amsCustomers')->find($id)) {
            $request->validate([
                'name' => 'required|max:255',
                'code' => 'required|max:255',
                'country_id' => 'required',
                'region_id' => 'required',
            ]);

            DB::beginTransaction();
            $customer->update($request->all());

            AMSCustomer::where('customer_id', $customer->id)->delete();
            foreach ($request->get('area_ams') as $value) {
                AMSCustomer::create([
                    'customer_id' => $customer->id,
                    'ams_id' => $value['ams']['id'],
                    'area_id' => $value['area']['id'],
                ]);
            }
            DB::commit();

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
        if ($customer = Customer::with('amsCustomers')->find($id)) {
            $customer->delete();
            // AMSCustomer::find($customer->);
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
