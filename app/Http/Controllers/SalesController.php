<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;

class SalesController extends Controller
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

        $user = auth()->user();

        $sales = Sales::with(
            'customer',
            'prospect',
            'maintenance',
            'hangar',
            'product',
            'acType',
            'engine',
            'component',
            'apu',
            'salesLevel'
        )->when($search, function ($query) use ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('customer', 'LIKE', "%$search%")
                    ->orWhere('prospect', 'LIKE', "%$search%")
                    ->orWhere('maintenance', 'LIKE', "%$search%")
                    ->orWhere('ac_reg', 'LIKE', "%$search%")
                    ->orWhere('apu', 'LIKE', "%$search%")
                    ->orWhere('component', 'LIKE', "%$search%")
                    ->orWhere('hangar', 'LIKE', "%$search%")
                    ->orWhere('engine', 'LIKE', "%$search%");
            });
        })->when(($order && $by), function ($query) use ($order, $by) {
            $query->orderBy($order, $by);
        })->paginate($paginate);

        $query_string = [
            'search' => $search,
            'order'  => $order,
            'by'     => $by,
        ];

        $sales->appends($query_string);

        // define empty array untuk menampung data [tabel salesplan]
        $data = [];
        foreach ($sales as $item) {
            $properties = $item->acType->name.'/'.$item->engine->name.'/'.$item->apu->name.'/'.$item->component->name; // kolom AC/ENG/APU/COMP di dashboard

            $data[] = [
                'customer' => $item->customer->name,
                'product' => $item->product->name,
                'properties' => $properties,
                'registration' => $item->ac_reg,
                'other' => $item->is_rkap ? 'RKAP' : 'NO-RKAP',
                'type' => $item->prospect->transactionType->name,
                'level' => $item->salesLevel->level->level,
                'progress' => '50%', // kumaha ngitung na?
                'status' => $item->status,
            ];
        }

        // data untuk 5 overview card [ter-atas]
        $total_target = auth()->user()->ams->ams_targets->sum('value'); // total [target]
        $total_open = $sales->where('salesLevel.status', 1)->sum('value'); // total [open]
        $total_closed = $sales->where('salesLevel.status', 2)->sum('value'); // total [closed]
        $total_cancel = $sales->where('salesLevel.status', 4)->sum('value'); // total [cancel]

        // menampung overview data untuk [4 card level]
        for ($i = 1; $i <= 4; $i++){
            $data_sales = $sales->where('salesLevel.level_id', $i); // get data sales [per-level]
            ${"level$i"} = [ // level[1-4]
                'total' => $data_sales->sum('value'), // total [all] sales 
                'open' => $data_sales->where('salesLevel.status', 1)->sum('value'), // total [open] sales
                'closed' => $data_sales->where('salesLevel.status', 2)->sum('value'), // total [closed] sales
                'closeIn' => $data_sales->where('salesLevel.status', 3)->sum('value'), // total [close-in] sales
                'cancel' => $data_sales->where('salesLevel.status', 4)->sum('value'), // total [cancel] sales
            ];
        }

        return response()->json([ 
            'success' => true,
            'message' => 'Retrieve data successfully',
            'data'    => [
                'totalTarget' => $total_target,
                'totalOpen' => $total_open,
                'totalClosed' => $total_closed,
                'totalOpenClosed' => $total_open + $total_closed,
                'totalCancel' => $total_cancel,
                'level4' => $level4,
                'level3' => $level3,
                'level2' => $level2,
                'level1' => $level1,
                'salesPlan' => $data,
            ]
        ], 200);
    }
}
