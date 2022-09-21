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

        // get all sales data
        $all_sales = Sales::with([
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
        ])->get();

        // get user sales data
        $sales_by_user = Sales::with([
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
        ])->when($search, function ($query) use ($search) {
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
        })->whereHas('prospect', function ($query) use ($user) {
            $query->where('pm_id', $user->id);
        })->paginate($paginate);

        $query_string = [
            'search' => $search,
            'order'  => $order,
            'by'     => $by,
        ];

        $sales_by_user->appends($query_string);

        // define empty array untuk menampung data [tabel salesplan user]
        $user_salesplan = [];
        foreach ($sales_by_user as $item) {
            $properties = $item->acType->name.'/'.$item->engine->name.'/'.$item->apu->name.'/'.$item->component->name; // kolom AC/ENG/APU/COMP di dashboard

            $user_salesplan[] = [
                'customer' => $item->customer->name,
                'product' => $item->product->name,
                'properties' => $properties,
                'registration' => $item->ac_reg,
                'other' => $item->is_rkap ? 'RKAP' : 'NO-RKAP',
                'type' => $item->prospect->transactionType->name,
                'level' => $item->salesLevel->level->level,
                'progress' => 50, // kumaha ngitung na?
                'status' => $item->status,
            ];
        }

        // data untuk 5 overview card [ter-atas] user sales
        $user_target = auth()->user()->ams->ams_targets->sum('value'); // total user sales [target]
        $user_open = $sales_by_user->where('salesLevel.status', 1)->sum('value'); // total user sales [open]
        $user_closed = $sales_by_user->where('salesLevel.status', 2)->sum('value'); // total user sales [closed]
        $user_cancel = $sales_by_user->where('salesLevel.status', 4)->sum('value'); // total user sales [cancel]

        // menampung overview data untuk [4 card level]
        for ($i = 1; $i <= 4; $i++){
            $user_sales = $sales_by_user->where('salesLevel.level_id', $i); // get data user sales [per-level]
            ${"level$i"} = [ // level[1-4]
                'total' => $user_sales->sum('value'), // user total [all] sales 
                'open' => $user_sales->where('salesLevel.status', 1)->sum('value'), // user total [open] sales
                'closed' => $user_sales->where('salesLevel.status', 2)->sum('value'), // user total [closed] sales
                'closeIn' => $user_sales->where('salesLevel.status', 3)->sum('value'), // user total [close-in] sales
                'cancel' => $user_sales->where('salesLevel.status', 4)->sum('value'), // user total [cancel] sales
            ];
        }

        // overview data (all sales) untuk [modal salesplan total]
        $all_open = $all_sales->where('salesLevel.status', 1)->sum('value'); // total all sales [open]
        $all_closed = $all_sales->where('salesLevel.status', 2)->sum('value'); // total all sales [closed]
        $all_cancel = $all_sales->where('salesLevel.status', 4)->sum('value'); // total all sales [cancel]
        $all_salesplan = [
            'totalOpen' => $all_open,
            'totalClosed' => $all_closed,
            'totalOpenClosed' => $all_open + $all_closed,
            'totalCancel' => $all_cancel,
        ];

        return response()->json([ 
            'success' => true,
            'message' => 'Retrieve data successfully',
            'data'    => [
                'all' => $all_salesplan,
                'user' => [
                    'totalTarget' => $user_target,
                    'totalOpen' => $user_open,
                    'totalClosed' => $user_closed,
                    'totalOpenClosed' => $user_open + $user_closed,
                    'totalCancel' => $user_cancel,
                    'level4' => $level4,
                    'level3' => $level3,
                    'level2' => $level2,
                    'level1' => $level1,
                    'salesPlan' => $user_salesplan,
                ],
            ]
        ], 200);
    }
}
