<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\AMSCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Helpers\PaginationHelper as PG;

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

        $raw = Customer::with('country.region')
                            ->search($search)
                            ->get();

        $customer = new Collection();
        
        foreach ($raw as $item) {
            $customer->push((object)[
                'id'  => $item->id,
                'name' => $item->name,
                'code' => $item->code,
                'group' => $item->group,
                'status' => $item->status,
                'country' => $item->country->name,
                'region' => $item->country->region->name,
            ]);
        }

        $customer = $customer->sortBy([[$order, $by]])->values();
        $data = PG::paginate($customer, $paginate);

        $data->appends([
            'search' => $search,
            'order' => $order,
            'by' => $by,
        ])->values();

        return response()->json([
            'message' => 'Success!',
            'data' => $data
        ], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'group_type' => 'required|integer|max:255',
            'region_id' => 'required',
            'country_id' => 'required|integer|exists:countries,id',
            'is_active' => 'required|boolean',
            'area_ams.*.ams.id' => 'required|integer|exists:ams,id',
            'area_ams.*.area.id' => 'required|integer|exists:areas,id'
        ]);

        DB::beginTransaction();

        $customer = new Customer;
        $customer->code = $request->code;
        $customer->name = $request->name;
        $customer->group_type = $request->group_type;
        $customer->country_id = $request->country_id;
        $customer->is_active = $request->is_active;
        $customer->save();

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
            'data' => $request->all(),
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
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255',
                'group_type' => 'required|integer|max:255',
                'country_id' => 'required|integer|exists:countries,id',
                'is_active' => 'required|boolean',
            ]);

            DB::beginTransaction();
            
            $customer->code = $request->code;
            $customer->name = $request->name;
            $customer->group_type = $request->group_type;
            $customer->country_id = $request->country_id;
            $customer->is_active = $request->is_active;
            $customer->push();

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
