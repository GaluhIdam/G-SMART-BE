<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\ContactPerson;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $type = $request->get('type');

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

        // get authenticated user info
        $user = auth()->user();

        // get all sales data
        $all_sales = Sales::with('salesLevel')->get();

        $sales_by_user = Sales::with([
            'customer',
            'prospect',
            'maintenance',
            'hangar',
            'product',
            'engine',
            'component',
            'apu',
            'salesLevel',
        ])->search($search)
        ->filter([$start_date, $end_date, $type])
        ->user($user->id)
        ->order([$order, $by])
        ->paginate($paginate)
        ->withQueryString();
        
        // TODO: check level status & approval status to get  progress dynamically
        
        // define empty collection untuk menampung data [tabel salesplan user]
        $user_salesplan = new Collection();

        foreach ($sales_by_user as $item) {
            $user_salesplan->push((object)[
                'id' => $item->id,
                'customer' => $item->customer->name,
                'product' => $item->product->name,
                'properties' => $item->tmb_properties,
                'registration' => $item->ac_reg,
                'other' => $item->other,
                'type' => $item->type,
                'level' => $item->level,
                'progress' => $item->progress,
                'status' => $item->status,
                'location' => $item->hangar->name,
                'maintenance' => $item->maintenance->description,
                'startDate' => Carbon::parse($item->start_date)->format('Y-m-d'),
                'endDate' => Carbon::parse($item->end_date)->format('Y-m-d'),
            ]);
        }

        // data untuk 5 overview card [ter-atas] user sales
        $user_target = auth()->user()->ams->amsTargets->sum('value'); // total user sales [target]
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
                'countOpen' => $user_sales->where('salesLevel.status', 1)->count(), // user [open] sales count
                'countClosed' => $user_sales->where('salesLevel.status', 2)->count(), // user [closed] sales count
                'countCloseIn' => $user_sales->where('salesLevel.status', 3)->count(), // user [close-in] sales count
                'countCancel' => $user_sales->where('salesLevel.status', 4)->count(), // user [cancel] sales count
            ];
        }

        // modifikasi data pagination
        $sales_by_user->setCollection($user_salesplan);

        // overview data (all sales) untuk [modal salesplan total]
        $all_open = $all_sales->where('salesLevel.status', 1)->sum('value'); // total all sales [open]
        $all_closed = $all_sales->where('salesLevel.status', 2)->sum('value'); // total all sales [closed]
        $all_cancel = $all_sales->where('salesLevel.status', 4)->sum('value'); // total all sales [cancel]

        $data = [
            'all' => [
                'totalOpen' => $all_open,
                'totalClosed' => $all_closed,
                'totalOpenClosed' => $all_open + $all_closed,
                'totalCancel' => $all_cancel,
            ],
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
                'salesPlan' => $sales_by_user,
            ]
        ];

        return response()->json([ 
            'success' => true,
            'message' => 'Retrieve data successfully',
            'data' => $data,
        ], 200);
    }

    public function show($id)
    {
        $sales = Sales::find($id);

        if (!$sales) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 400);
        }

        $all_cp = ContactPerson::all();
        
        $customer_cp = ContactPerson::whereHas('customer', function ($query) use ($sales) {
            $query->where('id', $sales->customer->id);
        })->paginate(10);

        $total_sales = $sales->value;
        if ($sales->prospect->transaction_type_id != 2) {
            $market_share = $sales->prospect->market_share;
            $deviasi = $market_share - $total_sales;
        } else {
            $market_share = null;
            $deviasi = null;
        }

        $sales->level;

        if ($sales->salesReschedule) {
            $sales_reschedule = [
                'id' => $sales->salesReschedule->id,
                'hangar' => $sales->hangar->name,
                'registration' => $sales->tmb_properties,
                'cboDate' => Carbon::parse($sales->salesReschedule->start_date)->format('d-m-Y'),
                'endDate' => Carbon::parse($sales->salesReschedule->end_date)->format('d-m-Y'),
                'tat' => $sales->salesReschedule->tat,
                'currentDate' => Carbon::now()->format('d-m-Y'),
                'salesMonth' => Carbon::parse($sales->start_date)->format('F'),
            ];
        }

        if ($sales->salesReject) {
            $sales_reject = [
                'id' => $sales->salesReject->id,
                'category' => $sales->salesReject->category,
                'reason' => $sales->salesReject->reason,
            ];
        }

        $data = collect([
            'user' => auth()->user(),
            'salesDetail' => [
                'id' => $sales->id,
                'customer' => $sales->customer->name,
                'registration' => $sales->ac_reg,
                'properties' => $sales->tmb_properties,
                'level' => $sales->level,
                'status' => $sales->status,
                'other' => $sales->other,
                'type' => $sales->type,
                'progress' => $sales->progress,
                'monthSales' => Carbon::parse($sales->start_date)->format('F'),
                'tat' => $sales->tat,
                'year' => $sales->prospect->year,
                'startDate' => Carbon::parse($sales->start_date)->format('d-m-Y'),
                'endDate' => Carbon::parse($sales->end_date)->format('d-m-Y'),
                'location' => $sales->hangar->name,
                'product' => $sales->product->name,
                'maintenance' => $sales->maintenance->description,
                'marketShare' => $market_share,
                'totalSales' => $total_sales,
                'deviasi' => $deviasi,
            ],
            'salesReschedule' => $sales_reschedule ?? null,
            'salesReject' => $sales_reject ?? null,
            'customerContactPersons' => $customer_cp,
            'allContactPersons' => $all_cp,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data successfully',
            'data' => $data,
        ], 200);
    }

    public function update($id)
    {
        $sales = Sales::find($id);

        if (!$sales) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 400);
        }


    }
}
