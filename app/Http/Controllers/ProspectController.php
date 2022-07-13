<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProspectController extends Controller
{
    public function index()
    {
        $data  = Prospect::with(
            'transaction_type_id',
            'prospect_type_id',
            'strategic_initiative_id',
            'pm_id',
            'customer_id',
        )->get();

        return $data;
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'year'                    => 'required',
                'transaction_type_id'     => 'required',
                'prospect_type_id'        => 'required',
                'strategic_initiative_id' => 'required',
                'pm_id'                   => 'required',
                'customer_id'             => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $prospect = Prospect::create([
            'year'                    => $request->get('year'),
            'transaction_type_id'     => $request->get('transaction_type_id'),
            'prospect_type_id'        => $request->get('prospect_type_id'),
            'strategic_initiative_id' => $request->get('strategic_initiative_id'),
            'pm_id'                   => $request->get('pm_id'),
            'customer_id'             => $request->get('customer_id'),
        ]);

        return response()->json([
            'message' => 'Data Created Successfully',
            'data'    => $prospect
        ], 200);
    }
}
