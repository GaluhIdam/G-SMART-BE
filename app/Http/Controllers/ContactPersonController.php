<?php

namespace App\Http\Controllers;

use App\Models\ContactPerson;
use App\Models\Sales;
use App\Models\SalesRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ContactPersonController extends Controller
{
    public function index(Request $request)
    {
        $customer_id = $request->get('customer');

        if ($customer_id) {
            $contact_persons = ContactPerson::byCustomer($customer_id)
                                            ->paginate(10)
                                            ->withQueryString();
        } else {
            $contact_persons = ContactPerson::all();
        }

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data successfully',
            'data' => $contact_persons,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            // 'email' => 'required|string|email:rfc,dns|unique:contact_persons,email', // TODO perlu konfirmasi
            'email' => 'required|string|email|unique:contact_persons,email',
            'address' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'status' => 'required|boolean',
            'sales_id' => 'required|integer|exists:sales,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        try {
            DB::beginTransaction();

            $sales = Sales::find($request->sales_id);
            $customer = $sales->customer;

            $customer_cp = new ContactPerson;
            $customer_cp->name = $request->name;
            $customer_cp->phone = $request->phone;
            $customer_cp->email = $request->email;
            $customer_cp->address = $request->address;
            $customer_cp->customer_id = $customer->id;
            $customer_cp->title = $request->title;
            $customer_cp->status = $request->status;
            $customer_cp->save();

            $active_cp = $sales->contact_persons->where('status', 1);
            $requirement = $sales->salesRequirements->where('requirement_id', 1);

            if ($requirement->isEmpty()) {
                $requirement = new SalesRequirement;
                $requirement->sales_id = $sales->id;
                $requirement->requirement_id = 1;
                $requirement->status = $active_cp->isNotEmpty() ?? 0;
                $requirement->save();
            } else {
                if ($requirement->count() > 1) {
                    foreach ($requirement as $item) {
                        if ($requirement->count() > 1) {
                            $item->delete();
                        }
                    }
                }
                $requirement = $requirement->first();
                $requirement->status = $active_cp->isNotEmpty() ?? 0;
                $requirement->push();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Contact person created successfully',
                'data' => $customer_cp,
            ], 200);
        } catch (QueryException $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy($id, Request $request)
    {
        $contact_person = ContactPerson::find($id);
        $sales = Sales::find($request->sales_id);
        
        if (!$contact_person || !$sales) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 400);
        }

        $contact_person->delete();
        
        $active_cp = $sales->contact_persons->where('status', 1);

        $requirement = $sales->salesRequirements->where('requirement_id', 1)->first();
        $requirement->status = $active_cp->isNotEmpty() ?? 0;
        $requirement->push();

        return response()->json([
            'success' => true,
            'message' => 'Contact person deleted successfully',
        ], 200);
    }
}
