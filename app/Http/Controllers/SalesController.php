<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\SalesLevel;
use App\Models\SalesRequirement;
use App\Models\Customer;
use App\Models\Prospect;
use App\Models\Requirement;
use App\Models\SalesReschedule;
use App\Models\XpreamPlanningGates;
use App\Models\SalesReject;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\Notification;
use App\Helpers\PaginationHelper as PG;

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
                            ->get();

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

        $table_salesplan = $table_salesplan->sortBy([[$order, $by]])->values();
        $salesplan = PG::paginate($table_salesplan, $paginate);

        $salesplan->appends([
            'search' => $search,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'type' => $type,
            'by' => $by,
        ]);

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
                'salesPlan' => $salesplan,
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
            'customer_id' => 'sometimes|required|integer|exists:customers,id',
            'prospect_id' => 'required|integer|exists:prospects,id',
            'product_id' => 'sometimes|required|integer|exists:products,id',
            'maintenance_id' => 'required|integer|exists:maintenances,id',
            'hangar_id' => 'required|integer|exists:hangars,id',
            'ac_type_id' => 'sometimes|required|integer|exists:ac_type_id,id',
            'ac_reg' => 'required|string',
            'value' => 'required|numeric',
            'tat' => 'required|integer',
            'start_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $prospect = Prospect::find($request->prospect_id);
            $customer = Customer::find($request->customer_id) ?? $prospect->amsCustomer->customer;

            $start_date = Carbon::parse($request->start_date);
            $tat = $request->tat;
            $end_date = Carbon::parse($request->start_date)->addDays($tat);

            $sales = new Sales;
            $sales->customer_id = $customer->id;
            $sales->prospect_id = $prospect->id;
            $sales->ac_reg = $request->ac_reg;
            $sales->value = $request->value;
            $sales->maintenance_id = $request->maintenance_id;
            $sales->hangar_id = $request->hangar_id;
            $sales->product_id = $request->product_id ?? null;
            $sales->ac_type_id = $request->ac_type_id ?? null;
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
                $requirement->status = ($i == 1 || $i == 4) ? 1 : 0;
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
            'pbth' => 'required|array',
            'pbth.*.month' => 'required|string',
            'pbth.*.value' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $prospect = Prospect::find($request->prospect_id);
            $customer = $prospect->amsCustomer->customer;
            $year = $prospect->year;
            $pbth = $request->pbth;
            
            $temp_sales = [];
            foreach ($pbth as $item) {
                $s_date = Carbon::parse("1 {$item['month']} {$year}");
                $e_date = Carbon::parse("1 {$item['month']} {$year}")->endOfMonth();
                
                $start_date = $s_date->format('Y-m-d');
                $end_date = $e_date->format('Y-m-d');
                $tat = $s_date->diffInDays($e_date);
                $value = $item['value'];

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
                    $level->status = ($i == 1) ? 1 : 2;
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
        $user = auth()->user();

        if ($user->hasRole('AMS')) {
            $ams = ($user->ams->id == $sales->ams_id);
        } else {
            $ams = true;
        }

        if (!$sales || !$ams) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 404);
        }

        $total_sales = $sales->value;
        $market_share = $sales->prospect->market_share;
        $deviasi = $market_share - $total_sales;

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
                'customer' => $sales->customer->only(['id', 'name', 'full_path']),
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
                'location' => $sales->hangar ?? null,
                'product' => $sales->product ?? null,
                'maintenance' => $sales->maintenance ?? null,
                'upgrade' => $sales->upgrade_level,
                'marketShare' => $market_share,
                'totalSales' => $total_sales,
                'deviasi' => $deviasi,
            ], 
            'salesReschedule' => $sales_reschedule ?? null,
            'salesReject' => $sales_reject ?? null,
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

    public function showTmbSales($id, Request $request)
    {
        $search = $request->get('search');
        $paginate = $request->paginate ?? 10;

        $data = Sales::with('hangar', 'maintenance')
                    ->when($search, function ($query) use ($search) {
                        $query->where('ac_reg', 'LIKE', "%$search%");
                    })
                    ->where('prospect_id', $id)
                    ->paginate($paginate);
                    
        $prospect = Prospect::find($id);
        $sales_plan = $prospect->sales_plan;
        $market_share = $prospect->market_share;
        $deviation = $market_share - $sales_plan;

        return response()->json([
            'logo' => $prospect->amsCustomer->customer->full_path,
            'salesplan' => $sales_plan,
            'market_share' => $market_share,
            'deviation' => $deviation,
            'customer' => $prospect->amsCustomer->customer,
            'sales' => $data
        ], 200);
    }

    public function deleteTmbSales($id)
    {
        if ($tmbSales = Sales::find($id)) {
            try {
                DB::beginTransaction();

                $levels = $tmbSales->salesLevel;
                $requirements = $tmbSales->salesRequirements;

                foreach ($levels as $level) {
                    $level->delete();
                }

                $temp_files = [];
                foreach ($requirements as $requirement) {
                    $files = $requirement->files;
                    foreach ($files as $file) {
                        $temp_files[] = (object)$file;
                        $file->delete();
                    }
                    $requirement->delete();
                }
                $tmbSales->delete();
                
                DB::commit();

                foreach ($temp_files as $file) {
                    if (Storage::disk('public')->exists($file->path)) {
                        Storage::disk('public')->delete($file->path);
                    }
                }
                
                return response()->json([
                    'message' => 'TMB Sales has been deleted successfully!',
                    'data'    => $tmbSales
                ], 200);
            } catch (QueryException $e) {
                DB::rollback();

                return response()->json([
                    'message' => $e->getMessage(),
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }
    }

    public function requestUpgrade(Request $request)
    {
        $request->validate([
            'sales_id' => 'required|integer|exists:sales,id',
            'user_id' => 'required|integer|exists:users,id',
            'target_url' => 'required|string',
        ]);

        $sales = Sales::find($request->sales_id);
        $user = User::find($request->user_id);

        if (!$sales->upgrade_level) {
            return response()->json([
                'success' => false,
                'message' => 'Oops, complete the requirement first',
            ], 422);
        }

        if (!$user->hasRole('TPR')) {
            return response()->json([
                'success' => false,
                'message' => 'The selected user does not have access as a TPR',
            ], 422);
        }

        $tpr_mail = $user->email;
        $tpr_name = $user->name;
        $link = env('FRONTEND_URL').$request->target_url;

        $data = [
            'type' => 1,
            'subject' => 'GSMART - New Request to Upgrade Sales Level',
            'body' => [
                'message' => 'You have new request to upgrade salesplan level.',
                'user_name' => $tpr_name,
                'link' => $link,
                'ams_name' => $sales->ams->user->name,
                'customer' => $sales->customer->name,
                'ac_reg' => $sales->ac_reg,
                'type' => $sales->type,
                'level' => $sales->level,
                'progress' => $sales->progress,
                'tat' => $sales->tat,
                'start_date' => Carbon::parse($sales->start_date)->format('d F Y'),
                'end_date' => Carbon::parse($sales->end_date)->format('d F Y'),
            ]
        ];

        try {
            $mail_sent = Mail::to($tpr_mail)->send(new Notification($data));

            if (!$mail_sent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Oops, the email request can not be sent',
                ], 422);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Sales level upgrade requested successfully',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Oops, something\'s wrong with the email request process',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function confirmUpgrade($id)
    {
        $sales = Sales::findOrFail($id);

        if (!$sales->upgrade_level) {
            return response()->json([
                'success' => false,
                'message' => 'Oops, sales level cannot be upgraded',
            ], 422);
        }

        $level = $sales->salesLevel->firstWhere('level_id', $sales->level);
        $level->status = 2;
        $level->push();

        return response()->json([
            'success' => true,
            'message' => 'Sales level upgraded successfully',
            'data' => $sales,
        ], 200);
    }

    public function cogsRequest(Request $request)
    {
        $request->validate([
            'sales_id' => 'required|integer|exists:sales,id',
            'user_id' => 'required|integer|exists:users,id',
            'target_url' => 'required|string',
        ]);

        $sales = Sales::find($request->sales_id);
        $user = User::find($request->user_id);

        if ($sales->level != 3) {
            return response()->json([
                'success' => false,
                'message' => 'Oops, this action only available at level 3',
            ], 422);
        }

        if (!$user->hasRole('CBO')) {
            return response()->json([
                'success' => false,
                'message' => 'The selected user does not have access as a CBO',
            ], 422);
        }

        $cbo_mail = $user->email;
        $cbo_name = $user->name;
        $link = env('FRONTEND_URL').$request->target_url;

        $data = [
            'type' => 1,
            'subject' => 'GSMART - New Request to Upload COGS',
            'body' => [
                'message' => 'You have new request to upload COGS.',
                'user_name' => $cbo_name,
                'link' => $link,
                'ams_name' => $sales->ams->user->name,
                'customer' => $sales->customer->name,
                'ac_reg' => $sales->ac_reg,
                'type' => $sales->type,
                'level' => $sales->level,
                'progress' => $sales->progress,
                'tat' => $sales->tat,
                'start_date' => Carbon::parse($sales->start_date)->format('d F Y'),
                'end_date' => Carbon::parse($sales->end_date)->format('d F Y'),
            ]
        ];

        try {
            $mail_sent = Mail::to($cbo_mail)->send(new Notification($data));

            if (!$mail_sent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Oops, the email request can not be sent',
                ], 422);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'COGS Upload requested successfully',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Oops, something\'s wrong with the email request process',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function slotRequest(Request $request)
    {
        $request->validate([
            'sales_id' => 'required|integer|exists:sales,id',
            'line_id' => 'required|integer|exists:lines,id',
            'user_id' => 'required|integer|exists:users,id',
            'target_url' => 'required|string',
        ]);

        $sales = Sales::find($request->sales_id);
        $sales->line_id = $request->line_id;
        $sales->push();

        $user = User::find($request->user_id);

        if ($sales->level != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Oops, this action only available at level 2',
            ], 422);
        }

        if (!$user->hasRole('CBO')) {
            return response()->json([
                'success' => false,
                'message' => 'The selected user does not have access as a CBO',
            ], 422);
        }

        $cbo_mail = $user->email;
        $cbo_name = $user->name;
        $link = env('FRONTEND_URL').$request->target_url;

        $data = [
            'type' => 2,
            'subject' => 'GSMART - New Hangar Slot Request',
            'body' => [
                'message' => 'You have new request for hangar slot.',
                'user_name' => $cbo_name,
                'ams_name' => $sales->ams->user->name,
                'hangar' => $sales->hangar->name,
                'line' => $sales->line->name,
                'ac_reg' => $sales->ac_reg,
                'tat' => $sales->tat,
                'start_date' => Carbon::parse($sales->start_date)->format('d F Y'),
                'end_date' => Carbon::parse($sales->end_date)->format('d F Y'),
                'link' => $link,
            ]
        ];

        try {
            $mail_sent = Mail::to($cbo_mail)->send(new Notification($data));

            if (!$mail_sent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Oops, the email request can not be sent',
                ], 422);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Hangar slot requested successfully',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Oops, something\'s wrong with the email request process',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function slotConfirm($id)
    {
        $sales = Sales::findOrFail($id);

        if (!$sales->line) {
            return response()->json([
                'success' => false,
                'message' => 'Oops, this sales does not have a line hangar yet',
            ], 422);
        }

        $sales->setRequirement(8);

        return response()->json([
            'success' => true,
            'message' => 'Line hangar approved successfully',
            'data' => $sales,
        ], 200);
    }

    public function inputSONumber($id, Request $request)
    {
        $so_number = XpreamPlanningGates::where('gsmart_id', $id)->first();
        if($so_number) {
            if($so_number->so) {
                DB::beginTransaction();
        
                $sales = Sales::findOrFail($id);
                $sales->so_number = $so_number;
                $sales->push();
        
                $sales->setRequirement(10);
        
                DB::commit();
        
                return response()->json([
                    'success' => true,
                    'message' => 'SO Number inputted successfully',
                    'data' => $sales,
                    'so_number' => $so_number,
                ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'SO Number is empty',
                    ], 200);            
                }
            } else {
            return response()->json([
                'success' => false,
                'message' => 'Gsmart ID Not Found',
            ], 200);            
        }
        
        // $request->validate(['so_number' => 'required|string']);
        // try {
        //     DB::beginTransaction();

        //     $sales = Sales::findOrFail($id);
        //     $sales->so_number = $request->so_number;
        //     $sales->push();

        //     $sales->setRequirement(10);

        //     DB::commit();

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'SO Number inputted successfully',
        //         'data' => $sales,
        //     ], 200);
        // } catch (QueryException $e) {
        //     DB::rollback();

        //     return response()->json([
        //         'success' => false,
        //         'message' => $e->getMessage(),
        //     ], 500);
        // }
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
            'ac_reg' => 'required|string',
            'value' => 'required|numeric',
            'tat' => 'required|integer',
            'start_date' => 'required|date',
        ]);

        $start_date = Carbon::parse($request->start_date);
        $tat = $request->tat;
        $end_date = Carbon::parse($request->start_date)->addDays($tat);
        
        $sales = Sales::findOrFail($id);
        $sales->maintenance_id = $request->maintenance_id;
        $sales->hangar_id = $request->hangar_id;
        $sales->ac_reg = $request->ac_reg;
        $sales->value = $request->value;
        $sales->tat = $tat;
        $sales->start_date = $start_date;
        $sales->end_date = $end_date;
        $sales->push();

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

    public function rejectSales($id, Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'reason' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $sales = Sales::findOrFail($id);

            $reject = new SalesReject;
            $reject->sales_id = $sales->id;
            $reject->category = $request->category;
            $reject->reason = $request->reason;
            $reject->save();

            $sales_level = $sales->salesLevel->firstWhere('level_id', $sales->level);
            $sales_level->status = 4;
            $sales_level->push();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sales rejected successfully',
                'data' => $sales,
            ], 200);
        } catch (QueryException $e) {
            DB::rollback();

            return response()->json([
                'success' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function closeSales($id)
    {
        $sales = Sales::findOrFail($id);
        $sales_level = $sales->salesLevel->firstWhere('level_id', $sales->level);

        if (($sales_level->level_id != 1) && ($sales_level->status != 2)) {
            return response()->json([
                'success' => false,
                'message' => 'Sales cannot be closed',
            ], 400);
        }

        $sales_level->status = 3;
        $sales_level->push();

        return response()->json([
            'success' => true,
            'message' => 'Sales closed successfully',
            'data' => $sales,
        ], 200);
    }
}
