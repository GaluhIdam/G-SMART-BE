<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\SalesLevel;
use App\Models\SalesRequirement;
use App\Models\Customer;
use App\Models\Prospect;
use App\Models\Requirement;
use App\Models\SalesReschedule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

        $raw_sales = Sales::with([
            'customer',
            'prospect',
            'maintenance',
            'hangar',
            'product',
            'engine',
            'component',
            'apu',
            'salesLevel',
            'ams',
        ])->search($search)
        ->filter([$start_date, $end_date, $type])
        ->user($user)
        ->sort([$order, $by])
        ->paginate($paginate)
        ->withQueryString();

        // define empty collection untuk menampung data [tabel salesplan user]
        $table_salesplan = new Collection();

        foreach ($raw_sales as $item) {
            $table_salesplan->push((object)[
                'id' => $item->id,
                'customer' => $item->customer->name,
                'product' => $item->product ? $item->product->name : null,
                'registration' => $item->registration,
                'acReg' => $item->ac_reg ?? null,
                'other' => $item->other,
                'type' => $item->type,
                'level' => $item->level,
                'progress' => $item->progress,
                'status' => $item->status,
                'location' => $item->hangar ? $item->hangar->name : null,
                'maintenance' => $item->maintenance ? $item->maintenance->description : null,
                'upgrade' => $item->upgrade_level,
                'startDate' => Carbon::parse($item->start_date)->format('Y-m-d'),
                'endDate' => Carbon::parse($item->end_date)->format('Y-m-d'),
            ]);
        }

        // data untuk 5 overview card [ter-atas] user sales
        if ($user->hasRole('AMS')) {
            $total_target = $user->ams->amsTargets->sum('target'); // total user sales [target]
        } else {
            $total_target = 0;
            foreach ($raw_sales as $sales) {
                if ($sales->ams) {
                    $total_target += $sales->ams->amsTargets->sum('target');
                }
            }
        }
        $total_open = $raw_sales->where('status', 'Open')->sum('value'); // total user sales [open]
        $total_closed = $raw_sales->where('status', 'Closed')->sum('value'); // total user sales [closed]
        $total_cancel = $raw_sales->where('status', 'Cancel')->sum('value'); // total user sales [cancel]

        // menampung overview data untuk [4 card level]
        for ($i = 1; $i <= 4; $i++){
            $user_sales = $raw_sales->where('level', $i); // get data user sales [per-level]
            ${"level$i"} = [ // level[1-4]
                'total' => $user_sales->sum('value'), // user total [all] sales 
                'open' => $user_sales->where('status', 'Open')->sum('value'), // user total [open] sales
                'closeIn' => $user_sales->where('status', 'Close in')->sum('value'), // user total [close-in] sales
                'closed' => $user_sales->where('status', 'Closed')->sum('value'), // user total [closed] sales
                'cancel' => $user_sales->where('status', 'Cancel')->sum('value'), // user total [cancel] sales
                'countOpen' => $user_sales->where('status', 'Open')->count(), // user [open] sales count
                'countCloseIn' => $user_sales->where('status', 'Close in')->count(), // user [close-in] sales count
                'countClosed' => $user_sales->where('status', 'Closed')->count(), // user [closed] sales count
                'countCancel' => $user_sales->where('status', 'Cancel')->count(), // user [cancel] sales count
            ];
        }

        // TODO temproray -> sorting in collection directly instead of eloquent query
        if ($order == 'level') {
            $table_salesplan = ($by == 'asc') ? $table_salesplan->sortBy('level')->values()
                                            : $table_salesplan->sortByDesc('level')->values();
        } else if ($order == 'status') {
            $table_salesplan = ($by == 'asc') ? $table_salesplan->sortBy('status')->values()
                                            : $table_salesplan->sortByDesc('status')->values();
        }

        // modifikasi data pagination
        $raw_sales->setCollection($table_salesplan);

        // overview data (all sales) untuk [modal salesplan total]
        $all_open = $all_sales->where('status', 'Open')->sum('value'); // total all sales [open]
        $all_closed = $all_sales->where('status', 'Closed')->sum('value'); // total all sales [closed]
        $all_cancel = $all_sales->where('status', 'Cancel')->sum('value'); // total all sales [cancel]

        $data = [
            'all' => [
                'totalOpen' => $all_open,
                'totalClosed' => $all_closed,
                'totalOpenClosed' => $all_open + $all_closed,
                'totalCancel' => $all_cancel,
            ],
            'user' => [
                'totalTarget' => $total_target,
                'totalOpen' => $total_open,
                'totalClosed' => $total_closed,
                'totalOpenClosed' => $total_open + $total_closed,
                'totalCancel' => $total_cancel,
                'level4' => $level4,
                'level3' => $level3,
                'level2' => $level2,
                'level1' => $level1,
                'salesPlan' => $raw_sales,
            ]
        ];

        return response()->json([ 
            'success' => true,
            'message' => 'Retrieve data successfully',
            'data' => $data,
        ], 200);
    }

    public function createTmb(Request $request)
    {
        $request->validate([
            'prospect_id' => 'required|integer|exists:prospects,id',
            'maintenance_id' => 'required|integer|exists:maintenances,id',
            'hangar_id' => 'required|integer|exists:hangars,id',
            'ac_reg' => 'required|string',
            'value' => 'required|numeric',
            'tat' => 'required|integer',
            'start_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $prospect = Prospect::find($request->prospect_id);
            $customer = $prospect->amsCustomer->customer;

            $start_date = Carbon::parse($request->start_date);
            $tat = $request->tat;
            $end_date = Carbon::parse($request->start_date)->addDays($tat);

            $sales = new Sales;
            $sales->customer_id = $customer->id;
            $sales->prospect_id = $prospect->id;
            $sales->ac_reg = $request->acreg;
            $sales->value = $request->value;
            $sales->maintenance_id = $request->maintenance_id;
            $sales->hangar_id = $request->hangar_id;
            $sales->tat = $tat;
            $sales->start_date = $start_date->format('Y-m-d');
            $sales->end_date = $end_date->format('Y-m-d');
            $sales->save();

            for ($i = 1; $i <= 4; $i++) { 
                $level = new SalesLevel;
                $level->level_id = $i;
                $level->sales_id = $sales->id;
                $level->status = 1;
                $level->save();
            }

            for ($i = 1; $i <= 10; $i++) { 
                $requirement = new SalesRequirement;
                $requirement->sales_id = $sales->id;
                $requirement->requirement_id = $i;
                if ($i == 1) {
                    $customer_cp = $sales->contact_persons;
                    $requirement->status = $customer_cp->isNotEmpty() ?? 0;
                } else if ($i == 4) {
                    $requirement->status = 1;
                }
                $requirement->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Salesplan created successfully',
                'data' => $sales,
            ], 200);
        } catch (QueryException $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function createPbth(Request $request)
    {
        $request->validate([
            'prospect_id' => 'required|integer|exists:prospects,id',
            'month' => 'required|array',
            'month.*' => 'required|integer',
            'value' => 'required|array',
            'value.*' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $prospect = Prospect::find($request->prospect_id);
            $customer = $prospect->amsCustomer->customer;;
            $year = $prospect->year;
            
            $temp_sales = [];
            foreach ($request->month as $months => $month) {
                $s_date = Carbon::create("{$year}-{$month}-1");
                $e_date = Carbon::create("{$year}-{$month}-1");
                
                $start_date = $s_date->format('Y-m-d');
                $end_date = $e_date->endOfMonth()->format('Y-m-d');
                $tat = $s_date->diffInDays($e_date);
                $value = $request->value[$months];

                $sales = new Sales;
                $sales->customer_id = $customer->id;
                $sales->prospect_id = $prospect->id;
                $sales->value = $value;
                $sales->tat = $tat;
                $sales->start_date = $start_date;
                $sales->end_date = $end_date;
                $sales->save();

                $temp_sales[] = $sales;

                for ($i = 1; $i <= 4; $i++) {  
                    $level = new SalesLevel;
                    $level->level_id = $i;
                    $level->sales_id = $sales->id;
                    $level->status = ($i == 1) ? 1 : 3;
                    $level->save();
                }

                for ($i = 1; $i <= 10; $i++) { 
                    $requirement = new SalesRequirement;
                    $requirement->sales_id = $sales->id;
                    $requirement->requirement_id = $i;
                    $requirement->status = ($i == 9) ? 0 : 1;
                    $requirement->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Salesplan created successfully',
                'data' => $temp_sales,
            ], 200);
        } catch (QueryException $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $sales = Sales::find($id);

        if (!$sales) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 404);
        }

        $total_sales = (int)$sales->value;
        if (!strcasecmp($sales->type, 'TMB')) {
            $market_share = $sales->prospect->market_share;
            $deviasi = $market_share - $total_sales;
        } else {
            $market_share = null;
            $deviasi = null;
        }

        if ($sales->salesReschedule) {
            $sales_reschedule = [
                'id' => $sales->salesReschedule->id,
                'hangar' => $sales->hangar->name,
                'registration' => $sales->registration,
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
                'customer' => $sales->customer->only(['id', 'name', 'logo_path']),
                'acReg' => $sales->ac_reg ?? null,
                'registration' => $sales->registration,
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
                'location' => $sales->hangar ? $sales->hangar->name : null,
                'product' => $sales->product ? $sales->product->name : null,
                'maintenance' => $sales->maintenance ? $sales->maintenance->description : null,
                'marketShare' => $market_share,
                'totalSales' => $total_sales,
                'deviasi' => $deviasi,
            ], 
            'salesReschedule' => $sales_reschedule ?? null,
            'salesReject' => $sales_reject ?? null, // TODO close/reject all activity where sales status was canceled
            'level4' => $sales->level4,
            'level3' => $sales->level3,
            'level2' => $sales->level2,
            'level1' => $sales->level1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data successfully',
            'data' => $data,
        ], 200);
    }

    public function upgradeLevel($id)
    {
        $sales = Sales::findOrFail($id);

        $level = $sales->salesLevel->firstWhere('level_id', $sales->level);
        $level->status = 2;
        $level->push();

        return response()->json([
            'success' => true,
            'message' => 'Sales level upgraded successfully',
            'data' => $sales,
        ], 200);
    }

    public function slotRequest($id, Request $request)
    {
        $request->validate(['line_id' => 'required|integer|exists:lines,id']);

        try {
            DB::beginTransaction();

            $sales = Sales::findOrFail($id);
            $sales->line_id = $request->line_id;
            $sales->push();

            $sales->setRequirement(8);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hangar slot requested successfully',
                'data' => $sales,
            ], 200);
        } catch (QueryException $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function inputSONumber($id, Request $request)
    {
        $request->validate(['so_number' => 'required|string']);

        try {
            DB::beginTransaction();

            $sales = Sales::findOrFail($id);
            $sales->so_number = $request->so_number;
            $sales->push();

            $sales->setRequirement(10);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'SO Number inputted successfully',
                'data' => $sales,
            ], 200);
        } catch (QueryException $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function switchAMS($id, Request $request)
    {
        $request->validate(['ams_id' => 'required|integer|exists:ams,id']);

        $sales = Sales::findOrFail($id);
        $sales->ams_id = $request->ams_id;
        $sales->push();

        return response()->json([
            'success' => true,
            'message' => 'Salesplan AMS changed successfully',
            'data' => $sales,
        ], 200);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'maintenance_id' => 'required|integer|exists:maintenances,id',
            'hangar_id' => 'required|integer|exists:hangars,id',
            'acReg' => 'required|string',
            'totalSales' => 'required|numeric',
            'tat' => 'required|integer',
            'start_date' => 'required|date',
        ]);

        $start_date = Carbon::parse($request->start_date);
        $tat = $request->tat;
        $end_date = Carbon::parse($request->start_date)->addDays($tat);
        
        $sales = Sales::findOrFail($id);
        $sales->maintenance_id = $request->maintenance_id;
        $sales->hangar_id = $request->hangar_id;
        $sales->ac_reg = $request->acReg;
        $sales->value = $request->totalSales;
        $sales->tat = $tat;
        $sales->start_date = $start_date;
        $sales->end_date = $end_date;
        $sales->save();

        return response()->json([
            'success' => true,
            'message' => 'Sales updated successfully',
            'data' => $sales,
        ], 200);
    }

    public function rescheduleSales($id, Request $request)
    {
        $request->validate([
            'hangar_id' => 'required|integer|exists:hangars,id',
            'current_date' => 'required|date',
            'start_date' => 'required|date',
            'tat' => 'required|integer',
        ]);

        $sales = Sales::findOrFail($id);
        $reschedule = $sales->salesReschedule;

        $start_date = Carbon::parse($request->start_date);
        $tat = $request->tat;
        $end_date = Carbon::parse($request->start_date)->addDays($tat);

        $reschedule = $reschedule ?? new SalesReschedule;
        $reschedule->sales_id = $sales->id;
        $reschedule->start_date = $start_date;
        $reschedule->end_date = $end_date;
        $reschedule->tat = $tat;
        $reschedule->hangar_id = $request->hangar_id;
        $reschedule->current_date = $request->current_date;
        $reschedule->save();

        return response()->json([
            'success' => true,
            'message' => 'Sales rescheduled successfully',
            'data' => $reschedule,
        ], 200);
    }
}
