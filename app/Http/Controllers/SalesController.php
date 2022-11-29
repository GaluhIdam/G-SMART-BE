<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Mail\Notification;
use App\Models\User;
use App\Models\Sales;
use App\Models\Customer;
use App\Models\Prospect;
use App\Models\SalesLevel;
use App\Models\SalesReject;
use App\Models\Requirement;
use App\Models\SalesReschedule;
use App\Models\SalesRequirement;
use App\Models\XpreamPlanningGates;
use App\Helpers\PaginationHelper as PG;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PbthSalesRequest;
use Illuminate\Support\Str;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $target = Sales::user($user)->thisYear()->rkap()->sum('value');
        $open = Sales::user($user)->thisYear()->open()->sum('value');
        $closed = Sales::user($user)->thisYear()->closed()->sum('value');
        $closein = Sales::user($user)->thisYear()->closeIn()->sum('value');
        $cancel = Sales::user($user)->thisYear()->cancel()->sum('value');

        for ($i = 1; $i <= 4; $i++){
            ${"level$i"} = [
                'total' => Sales::user($user)->thisYear()->level($i)->sum('value'),
                'open' => Sales::user($user)->thisYear()->level($i)->open()->sum('value'),
                'closed' => Sales::user($user)->thisYear()->level($i)->closed()->sum('value'),
                'closeIn' => Sales::user($user)->thisYear()->level($i)->closeIn()->sum('value'),
                'cancel' => Sales::user($user)->thisYear()->level($i)->cancel()->sum('value'),
                'countOpen' => Sales::user($user)->thisYear()->level($i)->open()->count(),
                'countClosed' => Sales::user($user)->thisYear()->level($i)->closed()->count(),
                'countCloseIn' => Sales::user($user)->thisYear()->level($i)->closeIn()->count(),
                'countCancel' => Sales::user($user)->thisYear()->level($i)->cancel()->count(),
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data successfully',
            'data' => [
                'totalTarget' => $target,
                'totalOpen' => $open,
                'totalClosed' => $closed,
                'totalCloseIn' => $closein,
                'totalCancel' => $cancel,
                'level4' => $level4,
                'level3' => $level3,
                'level2' => $level2,
                'level1' => $level1,
            ],
        ], 200);
    }

    public function table(Request $request)
    {
        $filters = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'customer' => $request->customer,
            'product' => $request->product,
            'ac_type' => $request->ac_type_id,
            'component' => $request->component_id,
            'engine' => $request->engine_id,
            'apu' => $request->apu_id,
            'ac_reg' => $request->acReg,
            'other' => $request->other,
            'level' => $request->level,
            'progress' => $request->progress,
            'status' => $request->status,
        ];

        $search = $request->search;
        $order = $request->order ?? 'id';
        $by = $request->by ?? 'desc';
        $paginate = $request->paginate ?? 10;

        $salesplan = Sales::search($search)
                            ->filter($filters)
                            ->user(auth()->user())
                            ->sort($order, $by)
                            ->paginate($paginate)
                            ->withQueryString();

        $data = new Collection();

        foreach ($salesplan as $sales) {
            $data->push((object)[
                'id' => $sales->id,
                'customer' => $sales->customer->name,
                'product' => $sales->product->name ?? null,
                'month' => $sales->month_sales,
                'registration' => $sales->registration,
                'acReg' => $sales->ac_reg ?? '-',
                'other' => $sales->other,
                'type' => $sales->type,
                'level' => $sales->level,
                'progress' => $sales->progress,
                'status' => $sales->status,
                'upgrade' => $sales->upgrade_level,
                'startDate' => Carbon::parse($sales->start_date)->format('Y-m-d'),
                'endDate' => Carbon::parse($sales->end_date)->format('Y-m-d'),
            ]);
        }

        $salesplan->setCollection($data);

        return response()->json([ 
            'success' => true,
            'message' => 'Retrieve data successfully',
            'data' => $salesplan,
        ], 200);
    }

    public function createTmb(Request $request)
    {
        $request->validate([
            'transaction_type_id' => 'sometimes|required|integer|between:1,2',
            'customer_id' => 'required|integer|exists:customers,id',
            'prospect_id' => 'sometimes|required|integer|exists:prospects,id',
            'maintenance_id' => 'required|integer|exists:maintenances,id',
            'product_id' => 'sometimes|required|integer|exists:products,id',
            'ac_type_id' => 'sometimes|required|integer|exists:ac_type_id,id',
            'ac_reg' => 'sometimes|required|string',
            'value' => 'sometimes|required|numeric',
            'tat' => 'required|integer',
            'start_date' => 'required|date',
            'is_rkap' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            $user = auth()->user();
            $sales = new Sales;

            $start_date = Carbon::parse($request->start_date);
            $end_date = Carbon::parse($request->start_date)->addDays($request->tat);

            if (!$request->is_rkap) {
                $sales->customer_id = $request->customer_id;
                $sales->transaction_type_id = $request->transaction_type_id;
                $sales->product_id = $request->product_id;
                $sales->ac_type_id = $request->ac_type_id;
                $sales->ams_id = $user->hasRole('AMS') ? $user->ams->id : null;
            } else {
                $prospect = Prospect::find($request->prospect_id);
                $sales->prospect_id = $prospect->id;
                $sales->customer_id = $prospect->amsCustomer->customer->id;
                $sales->transaction_type_id = $prospect->transaction_type_id;
                $sales->product_id = $prospect->tmb->product_id;
                $sales->ac_type_id = $prospect->tmb->ac_type_id ?? null;
                $sales->component_id = $prospect->tmb->component_id ?? null;
                $sales->engine_id = $prospect->tmb->engine_id ?? null;
                $sales->apu_id = $prospect->tmb->apu_id ?? null;
                $sales->ams_id = $prospect->amsCustomer->ams_id;
            }
            $sales->ac_reg = $request->ac_reg ?? null;
            $sales->value = $request->value;
            $sales->maintenance_id = $request->maintenance_id;
            $sales->is_rkap = $request->is_rkap;
            $sales->tat = $request->tat;
            $sales->start_date = $start_date->format('Y-m-d');
            $sales->end_date = $end_date->format('Y-m-d');
            $sales->save();

            $level = new SalesLevel;
            $level->level_id = 4;
            $level->sales_id = $sales->id;
            $level->status = 1;
            $level->save();

            for ($i = 1; $i <= 10; $i++) { 
                $requirement = new SalesRequirement;
                $requirement->sales_id = $sales->id;
                $requirement->requirement_id = $i;
                $requirement->status = ($i == 1 || $i == 5) ? 1 : 0;
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

    // TODO: confirmation needed!!
    public function createPbth(PbthSalesRequest $request)
    {
        try {
            DB::beginTransaction();

            $prospect = Prospect::find($request->prospect_id);
            $customer = $prospect->amsCustomer->customer;
            $year = $prospect->year;
            $month = $request->month;
            
                $s_date = Carbon::parse("1 {$month} {$year}");
                $e_date = Carbon::parse("1 {$month} {$year}")->endOfMonth();
                
                $start_date = $s_date->format('Y-m-d');
                $end_date = $e_date->format('Y-m-d');
                $tat = $s_date->diffInDays($e_date);
                $value = $request->value;

                $sales = new Sales;
                $sales->customer_id = $customer->id;
                $sales->prospect_id = $prospect->id;
                $sales->product_id = $prospect->pbth->product_id;
                $sales->ac_type_id = $prospect->pbth->ac_type_id;
                $sales->ams_id = $prospect->amsCustomer->ams_id;
                $sales->transaction_type_id = 3;
                $sales->value = $value;
                $sales->is_rkap = 1;
                $sales->tat = $tat;
                $sales->start_date = $start_date;
                $sales->end_date = $end_date;
                $sales->save();

                $level = new SalesLevel;
                $level->level_id = 1;
                $level->sales_id = $sales->id;
                $level->status = 1;
                $level->save();

                for ($i = 1; $i <= 10; $i++) { 
                    $requirement = new SalesRequirement;
                    $requirement->sales_id = $sales->id;
                    $requirement->requirement_id = $i;
                    $requirement->status = ($i == 9) ? 0 : 1;
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

    public function show($id)
    {
        $sales = Sales::find($id);
        $user = auth()->user();

        // TODO: authorize only registered AMS
        // if ($user->hasRole('AMS')) {
        //     $ams = ($user->ams->id == $sales->prospect->amsCustomer->ams->user_id);
        // } else {
        //     $ams = true;
        // }

        if (!$sales) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 404);
        }

        if ($sales->salesReschedule) {
            $sales_reschedule = [
                'id' => $sales->salesReschedule->id,
                'hangar' => $sales->hangar ?? null,
                'line' => $sales->line ?? null,
                'registration' => $sales->ac_reg ?? '-',
                'startDate' => Carbon::parse($sales->salesReschedule->start_date)->format('d-m-Y'),
                'endDate' => Carbon::parse($sales->salesReschedule->end_date)->format('d-m-Y'),
                'tat' => $sales->salesReschedule->tat,
                'currentDate' => $sales->start_date,
                'salesMonth' => Carbon::parse($sales->start_date)->format('F'),
            ];
        }

        if ($sales->salesReject) {
            $sales_reject = [
                'id' => $sales->salesReject->id,
                'category' => $sales->salesReject->category->name,
                'reason' => $sales->salesReject->reason,
            ];
        }

        $total_sales = $sales->value;
        $market_share = $sales->market_share;
        $deviation = $market_share - $total_sales;

        $data = collect([
            'user' => auth()->user(),
            'salesDetail' => [
                'id' => $sales->id,
                'customer' => $sales->customer->only(['id', 'name']),
                'acReg' => $sales->ac_reg ?? '-',
                'registration' => $sales->registration,
                'level' => $sales->level,
                'status' => $sales->status,
                'other' => $sales->other,
                'type' => $sales->type,
                'progress' => $sales->progress,
                'monthSales' => $sales->month_sales,
                'tat' => $sales->tat,
                'year' => $sales->year,
                'startDate' => Carbon::parse($sales->start_date)->format('d-m-Y'),
                'endDate' => Carbon::parse($sales->end_date)->format('d-m-Y'),
                'product' => $sales->product,
                'location' => $sales->hangar_name,
                'maintenance' => $sales->maintenance_name,
                'upgrade' => $sales->upgrade_level,
                'marketShare' => $market_share,
                'totalSales' => $total_sales,
                'deviasi' => $deviation,
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
        $search = $request->search ?? false;
        $order = $request->order ?? 'id';
        $by = $request->by ?? 'desc';
        $paginate = $request->paginate ?? 10;

        $prospect = Prospect::findOrFail($id);
        $sales_plan = $prospect->sales_plan;
        $market_share = $prospect->market_share;
        $deviation = $market_share - $sales_plan;

        $data = Sales::search($search)
                    ->where('prospect_id', $prospect->id)
                    ->get();
        
        $sales_by_prospect = new Collection();

        foreach ($data as $sales) {
            $sales_by_prospect->push((object)[
                'id' => $sales->id,
                'registration' => $sales->ac_reg,
                'maintenance' => $sales->maintenance_name,
                'location' => $sales->hangar_name,
                'sales_plan' => $sales->value,
                'tat' => $sales->tat,
                'start_date' => Carbon::parse($sales->start_date)->format('Y-m-d'),
                'end_date' => Carbon::parse($sales->end_date)->format('Y-m-d'),
                'level' => $sales->level,
                'status' => $sales->status,
                'customer' => $sales->customer,
            ]);
        }

        $sales_by_prospect = $sales_by_prospect->sortBy([[$order, $by]])->values();
        $salesplan = PG::paginate($sales_by_prospect, $paginate);

        $salesplan->appends([
            'search' => $search,
            'order' => $order,
            'by' => $by,
        ]);

        return response()->json([
            'salesplan' => $sales_plan,
            'market_share' => $market_share,
            'deviation' => $deviation,
            'customer' => $prospect->amsCustomer->customer,
            'sales' => $salesplan,
        ], 200);
    }

    public function deleteTmbSales($id)
    {
        if ($tmbSales = Sales::find($id)) {
            try {
                DB::beginTransaction();

                $requirements = $tmbSales->salesRequirements;

                $tmbSales->salesLevel->delete();

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
            'subject' => 'New Request to Upgrade Sales Level',
            'body' => [
                'message' => 'You have new request to upgrade salesplan level.',
                'user_name' => $tpr_name,
                'link' => $link,
                'ams_name' => $sales->ams->user->name ?? '-',
                'customer' => $sales->customer->name,
                'ac_reg' => $sales->ac_reg ?? '-',
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

    public function approveUpgrade($id)
    {
        $sales = Sales::findOrFail($id);

        $message = ($sales->level == 1) ? 'closed' : 'upgraded';

        if (!$sales->upgrade_level) {
            return response()->json([
                'success' => false,
                'message' => "Oops, sales level cannot be {$message}",
            ], 422);
        }

        $sales_level = $sales->salesLevel;
        if ($sales_level->level_id == 1) {
            $sales_level->status = 2;
        } else {
            $sales_level->level_id = $sales_level->level_id-1;
        }
        $sales_level->push();

        return response()->json([
            'success' => true,
            'message' => "Sales level {$message} successfully",
            'data' => $sales,
        ], 200);
    }

    public function requestCOGS(Request $request)
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
                'message' => 'Oops, this action only available in level 3',
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
            'subject' => 'New Request to Upload COGS',
            'body' => [
                'message' => 'You have new request to upload COGS.',
                'user_name' => $cbo_name,
                'link' => $link,
                'ams_name' => $sales->ams->user->name ?? '-',
                'customer' => $sales->customer->name,
                'ac_reg' => $sales->ac_reg ?? '-',
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

    public function requestHangar(Request $request)
    {
        $request->validate([
            'sales_id' => 'required|integer|exists:sales,id',
            'hangar_id' => 'required|integer|exists:hangars,id',
            'line_id' => 'required|integer|exists:lines,id',
            'user_id' => 'required|integer|exists:users,id',
            'target_url' => 'required|string',
        ]);

        $sales = Sales::find($request->sales_id);
        $sales->hangar_id = $request->hangar_id;
        $sales->line_id = $request->line_id;
        $sales->push();

        $user = User::find($request->user_id);

        if ($sales->level != 4) {
            return response()->json([
                'success' => false,
                'message' => 'Oops, this action only available at level 4',
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
            'subject' => 'New Slot Hangar Request',
            'body' => [
                'message' => 'You have new request for slot Hangar.',
                'user_name' => $cbo_name,
                'ams_name' => $sales->ams->user->name ?? '-',
                'hangar' => $sales->hangar_name,
                'line' => $sales->line_name,
                'ac_reg' => $sales->ac_reg ?? '-',
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
                'message' => 'Oops, something\'s wrong with the email request process. Please check your connection',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function approveHangar($id, Request $request)
    {
        $request->validate([
            'is_approved' => 'required|boolean',
            'target_url' => 'required|string',
        ]);

        $sales = Sales::findOrFail($id); 

        if (!$sales->line && !$sales->hangar) {
            return response()->json([
                'success' => false,
                'message' => 'Oops, this sales does not have a Line Hangar yet',
            ], 422);
        }

        if ($request->is_approved) {
            $status = 'approved';
            $sales->setRequirement(4);
        } else {
            $cbo = auth()->user();
            $ams = $sales->ams->user;
            $link = env('FRONTEND_URL').$request->target_url;

            $data = [
                'type' => 20,
                'subject' => 'Your Hangar Slot Request Rejected',
                'body' => [
                    'message' => 'Your Hangar slot request was rejected by CBO.',
                    'user_name' => $ams->name,
                    'cbo_name' => $cbo->name,
                    'hangar' => $sales->hangar_name,
                    'line' => $sales->line_name,
                    'ac_reg' => $sales->ac_reg ?? '-',
                    'tat' => $sales->tat,
                    'start_date' => Carbon::parse($sales->start_date)->format('d F Y'),
                    'end_date' => Carbon::parse($sales->end_date)->format('d F Y'),
                    'link' => $link,
                ]
            ];

            $status = 'rejected';
            $sales->hangar_id = null;
            $sales->line_id = null;
            $sales->push();

            Mail::to($ams->email)->send(new Notification($data));
        }

        return response()->json([
            'success' => true,
            'message' => "Line Hangar {$status} successfully",
            'data' => $sales,
        ], 200);
    }

    public function requestReschedule(Request $request)
    {
        $request->validate([
            'sales_id' => 'required|integer|exists:sales,id',
            'start_date' => 'required|date|after:current_date',
            'current_date' => 'required|date',
            'tat' => 'required|integer',
            'hangar_id' => 'required|integer|exists:hangars,id',
            'line_id' => 'required|integer|exists:lines,id',
            'user_id' => 'required|integer|exists:users,id',
            'target_url' => 'required|string',
        ]);

        $start_date = Carbon::parse($request->start_date);
        $tat = $request->tat;
        $end_date = Carbon::parse($request->start_date)->addDays($tat);

        $user = User::find($request->user_id);
        $sales = Sales::find($request->sales_id);

        $reschedule = $sales->salesReschedule ?? new SalesReschedule;
        $reschedule->sales_id = $sales->id;
        $reschedule->hangar_id = $request->hangar_id;
        $reschedule->line_id = $request->line_id;
        $reschedule->start_date = $start_date;
        $reschedule->end_date = $end_date;
        $reschedule->tat = $tat;
        $reschedule->current_date = $sales->start_date;
        $reschedule->save();

        $cbo_mail = $user->email;
        $cbo_name = $user->name;
        $link = env('FRONTEND_URL').$request->target_url;

        $data = [
            'type' => 3,
            'subject' => 'New Reschedule Sales Request',
            'body' => [
                'message' => 'You have new request for Reschedule Sales.',
                'user_name' => $cbo_name,
                'ams_name' => $sales->ams->user->name ?? '-',
                'customer' => $sales->customer->name,
                'hangar' => $sales->hangar_name,
                'line' => $sales->line_name,
                'ac_reg' => $sales->ac_reg ?? '-',
                'tat' => $sales->tat,
                'start_date' => Carbon::parse($sales->start_date)->format('d F Y'),
                'end_date' => Carbon::parse($sales->end_date)->format('d F Y'),
                'link' => $link,
                'new_hangar' => "Hangar {$reschedule->hangar->name}",
                'new_line' => "Line {$reschedule->line->name}",
                'new_tat' => $reschedule->tat,
                'new_s_date' => Carbon::parse($reschedule->start_date)->format('d F Y'),
                'new_e_date' => Carbon::parse($reschedule->end_date)->format('d F Y'),
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
                    'message' => 'Reschedule sales requested successfully',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Oops, something\'s wrong with the email request process. Please check your connection',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function approveReschedule($id, Request $request)
    {
        $request->validate([
            'is_approved' => 'required|boolean',
            'target_url' => 'required|string',
        ]);

        $sales = Sales::findOrFail($id);
        $reschedule = $sales->salesReschedule;

        if (!$reschedule) {
            return response()->json([
                'success' => false,
                'message' => 'Oops, this sales does not have active reschedule',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $status = $request->is_approved ? 'approved' : 'rejected';

            if ($request->is_approved) {
                $sales_year = Carbon::parse($sales->start_date)->format('Y');
                $reschedule_year = Carbon::parse($reschedule->start_date)->format('Y');
                
                if ($sales_year != $reschedule_year) {
                    $prospect = $sales->prospect ?? null;

                    if ($prospect) {
                        $new_prospect = $prospect->replicate();
                        $new_prospect->year = $reschedule_year;
                        $new_prospect->save();

                        if ($prospect->tmb) {
                            $new_tmb = $prospect->tmb->replicate();
                            $new_tmb->prospect_id = $new_prospect->id;
                            $new_tmb->save();
                        }
                        
                        if ($prospect->pbth) {
                            $new_pbth = $prospect->pbth->replicate();
                            $new_pbth->prospect_id = $new_prospect->id;
                            $new_pbth->save();
                        }
                    }

                    $new_sales = $sales->replicate();
                    $new_sales->prospect_id = $new_prospect->id ?? null;
                    $new_sales->hangar_id = $reschedule->hangar_id;
                    $new_sales->line_id = $reschedule->line_id;
                    $new_sales->tat = $reschedule->tat;
                    $new_sales->start_date = $reschedule->start_date;
                    $new_sales->end_date = $reschedule->end_date;
                    $new_sales->save();

                    $new_sales_level = $sales->salesLevel->replicate();
                    $new_sales_level->sales_id = $new_sales->id;
                    $new_sales_level->save();

                    $sales_requirements = $sales->salesRequirements;
                    foreach ($sales_requirements as $requirement) {
                        $new_requirement = $requirement->replicate();
                        $new_requirement->sales_id = $new_sales->id;
                        $new_requirement->save();
                        
                        foreach ($requirement->files as $file) {
                            $new_file = $file->replicate();
                            $new_file->requirement_id = $new_requirement->id;
                            $new_file->save();
                        }
                    }

                    $sales_level = $sales->salesLevel;
                    $sales_level->status = 4;
                    $sales_level->push();

                    $cancel = new SalesReject;
                    $cancel->sales_id = $sales->id;
                    $cancel->category_id = 4;
                    $cancel->reason = "Cancelled by System - Sales Plan has been rescheduled to the next year";
                    $cancel->save();

                    $data = $new_sales;
                } else {
                    $sales->hangar_id = $reschedule->hangar_id;
                    $sales->line_id = $reschedule->line_id;
                    $sales->tat = $reschedule->tat;
                    $sales->start_date = $reschedule->start_date;
                    $sales->end_date = $reschedule->end_date;
                    $sales->push();

                    $data = $sales;
                }
            }

            $reschedule->delete();

            $cbo = auth()->user();
            $ams = $sales->ams->user;
            $link = env('FRONTEND_URL').$request->target_url;

            $data = [
                'type' => 30,
                'subject' => 'Your Reschedule Sales Request '.Str::title($status),
                'body' => [
                    'message' => "Your request for rescheduling sales has been {$status}",
                    'user_name' => $ams->name,
                    'customer' => $sales->customer->name,
                    'hangar' => $sales->hangar_name,
                    'line' => $sales->line_name,
                    'ac_reg' => $sales->ac_reg ?? '-',
                    'tat' => $sales->tat,
                    'start_date' => Carbon::parse($sales->start_date)->format('d F Y'),
                    'end_date' => Carbon::parse($sales->end_date)->format('d F Y'),
                    'link' => $link,
                ]
            ];

            DB::commit();

            Mail::to($ams->email)->send(new Notification($data));

            return response()->json([
                'success' => true,
                'message' => "Reschedule sales {$status} successfully",
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

    public function requestCancel(Request $request)
    {
        $request->validate([
            'sales_id' => 'required|integer|exists:sales,id',
            'category_id' => 'required|integer|exists:cancel_categories,id',
            'reason' => 'required|string|min:50',
            'user_id' => 'required|integer|exists:users,id',
            'target_url' => 'required|string',
        ]);

        $sales = Sales::find($request->sales_id);
        $user = User::find($request->user_id);

        $cancel = $sales->salesReject ?? new SalesReject;
        $cancel->sales_id = $sales->id;
        $cancel->category_id = $request->category_id;
        $cancel->reason = $request->reason;
        $cancel->save();

        $cbo_mail = $user->email;
        $cbo_name = $user->name;
        $link = env('FRONTEND_URL').$request->target_url;

        $data = [
            'type' => 4,
            'subject' => 'New Cancel Sales Request',
            'body' => [
                'message' => 'You have new request for Cancel Sales.',
                'user_name' => $cbo_name,
                'ams_name' => $sales->ams->user->name ?? '-',
                'customer' => $sales->customer->name,
                'ac_reg' => $sales->ac_reg ?? '-',
                'tat' => $sales->tat,
                'start_date' => Carbon::parse($sales->start_date)->format('d F Y'),
                'end_date' => Carbon::parse($sales->end_date)->format('d F Y'),
                'category' => $cancel->category->name,
                'reason' => $cancel->reason,
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
                    'message' => 'Cancel sales requested successfully',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Oops, something\'s wrong with the email request process. Please check your connection',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function approveCancel($id, Request $request)
    {
        $request->validate([
            'is_approved' => 'required|boolean',
            'target_url' => 'required|string',
        ]);

        $sales = Sales::findOrFail($id);
        $cancel = $sales->salesReject;

        if (!$cancel) {
            return response()->json([
                'success' => false,
                'message' => 'Oops, this sales does not have active cancel request',
            ], 422);
        }

        if ($request->is_approved) {
            $status = 'approved';
            $sales_level = $sales->salesLevel;
            $sales_level->status = 4;
            $sales_level->push();
        } else {
            $status = 'rejected';
            $cancel->delete();
        }

        $cbo = auth()->user();
        $ams = $sales->ams->user;
        $link = env('FRONTEND_URL').$request->target_url;

        $data = [
            'type' => 40,
            'subject' => 'Your Cancel Sales Request '.Str::title($status),
            'body' => [
                'message' => "Your request for canceling sales has been {$status}",
                'user_name' => $ams->name,
                'customer' => $sales->customer->name,
                'ac_reg' => $sales->ac_reg ?? '-',
                'tat' => $sales->tat,
                'start_date' => Carbon::parse($sales->start_date)->format('d F Y'),
                'end_date' => Carbon::parse($sales->end_date)->format('d F Y'),
                'link' => $link,
            ]
        ];

        Mail::to($ams->email)->send(new Notification($data));

        return response()->json([
            'success' => true,
            'message' => "Cancel sales {$status} successfully",
            'data' => $sales,
        ], 200);
    }

    public function inputSONumber($id, Request $request)
    {
        $request->validate(['so_number' => 'required|string']);

        try {
            DB::beginTransaction();

            $sales = Sales::findOrFail($id);
            $wo_po = $sales->salesRequirements->where('requirement_id', 9)->first();
            if($wo_po->status != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please complete your WO/PO Number requirement!',
                ], 422);
            }

            $sales->so_number = $request->so_number;
            $sales->push();

            $sales_level = $sales->salesLevel;
            $sales_level->status = 3;
            $sales_level->save();

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

    public function acReg()
    {
        $ac_regs = Sales::select('ac_reg')->distinct()->pluck('ac_reg');

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data successfully',
            'data' => $ac_regs,
        ], 200);
    }
}
