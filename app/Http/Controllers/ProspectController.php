<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\TMB;
use App\Models\PBTH;
use App\Models\Sales;
use App\Models\Customer;
use App\Models\Prospect;
use App\Models\ProspectTMB;
use App\Models\ProspectPBTH;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Helpers\PaginationHelper as PG;

class ProspectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $filter = $request->filter ?? null;
        $order = $request->order ?? null;
        $by = $request->by ?? null;
        $paginate = $request->paginate ?? 10;

        $user = auth()->user();

        $market_share = Prospect::user($user)->marketYearAgo();
        $total_sales = Sales::user($user)->salesYearAgo();
        $prospects  = Prospect::search($search)
                        ->filter($filter)
                        ->user($user)
                        ->get();

        $grouped_by_customer = $prospects->groupBy('ams_customer_id')->values();

        $prospect_by_customer = new Collection();

        foreach ($grouped_by_customer as $prospect) {
            $years = array_filter(array_unique($prospect->pluck('year')->toArray()));
            $transactions = array_filter(array_unique($prospect->pluck('transaction')->toArray()));
            $types = array_filter(array_unique($prospect->pluck('type')->toArray()));
            $strategic_inits = array_filter(array_unique($prospect->pluck('strategic_init')->toArray()));

            $prospect_by_customer->push((object)[
                'year' => implode(', ', $years),
                'transaction' => implode(', ', $transactions),
                'type' => implode(', ', $types),
                'strategicInitiative' => implode(', ', $strategic_inits),
                'prjoectManager' => $prospect->first()->project_manager,
                'customer' => $prospect->first()->customer,
                'ams' => $prospect->first()->ams,
                'marketShare' => $prospect->sum('market_share'),
                'salesPlan' => $prospect->sum('sales_plan'),
            ]);
        }

        $prospect_by_customer = $prospect_by_customer->sortBy([[$order, $by]])->values();
        
        $data = PG::paginate($prospect_by_customer, $paginate);;

        $data->appends([
            'search' => $search,
            'filter' => $filter,
            'order' => $order,
            'by' => $by,
        ])->values();

        return response()->json([
            'status' => 'Success!',
            'message' => 'Successfully Get Prospect',
            'data' => [
                'prospect' => $data,
                'totalMarketShare' => $market_share,
                'totalSalesPlan' => $total_sales,
                'deviation' => $market_share - $total_sales,
            ]
        ], 200);
    }

    public function tmbOnly(Request $request)
    {
        $customer = $request->customer;
        
        $prospects = Prospect::with([
                                'transactionType',
                                'prospectType',
                                'strategicInitiative',
                                'pm',
                                'amsCustomer',
                            ])->whereIn('transaction_type_id', [1,2])
                            ->whereHas('amsCustomer', function ($query) use ($customer) {
                                $query->where('customer_id', $customer);
                            })->get();

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data successfully',
            'data' => $prospects,
        ], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'prospect_type_id' => 'required|integer|between:1,2',
            'transaction_type_id' => 'required|integer|between:1,2',
        ]);

        $prospect_type = $request->prospect_type_id;
        $transaction_type = $request->transaction_type_id;
        
        if ($prospect_type == 1) {
            $p_type = 'Organic';
            $prospect_rules = [
                'year' => 'required|date_format:Y',
                'ams_customer_id' => 'required|integer|exists:ams,id',
            ];
        } else if ($prospect_type == 2) {
            $p_type = 'In-organic';
            $prospect_rules = [
                'year' => 'required|date_format:Y',
                'ams_customer_id' => 'required|integer|exists:ams,id',
                'strategic_initiative_id' => 'required|integer|exists:strategic_initiatives,id',
                'pm_id' => 'required|integer|exists:users,id',
            ];
        }

        if (in_array($transaction_type, [1,2])) {
            $t_type = 'TMB';
            $transaction_rules = [
                'tmb' => 'required|array',
                'tmb.*.product' => 'required|array',
                'tmb.*.product.*.product_id' => 'required|integer|exists:products,id',
                'tmb.*.product.*.aircraft_type.id' => 'required|integer|exists:ac_type_id,id',
                'tmb.*.product.*.market_share' => 'required|numeric',
                'tmb.*.product.*.remark' => 'required|string',
                'tmb.*.product.*.maintenance_id.id' => 'required|integer|exists:maintenances,id',
            ];
        } else if ($transaction_type == 3) {
            $t_type = 'PBTH';
            $transaction_rules = [
                'pbth' => 'required|array',
                'pbth.*.product_id' => 'required|integer|exists:products,id',
                'pbth.*.aircraft_type_id' => 'required|integer|exists:ac_type_id,id',
                'pbth.*.target' => 'required|array',
                'pbth.*.target.*.month' => 'required|date_format:F',
                'pbth.*.target.*.rate' => 'required|numeric',
                'pbth.*.target.*.flight_hour' => 'required|numeric',
            ];
        }

        $request->validate(array_merge($prospect_rules, $transaction_rules));

        try {
            DB::beginTransaction();

            $prospect = new Prospect;
            $prospect->prospect_type_id = $prospect_type;
            $prospect->transaction_type_id = $transaction_type;
            $prospect->year = $request->year;
            $prospect->ams_customer_id = $request->ams_customer_id;
            $prospect->strategic_initiative_id = $request->strategic_initiative_id ?? null;
            $prospect->pm_id = $request->pm_id ?? null;
            $prospect->save();

            if (in_array($transaction_type, [1,2])) {
                foreach ($request->tmb as $product) {
                    foreach ($product['product'] as $data) {
                        $tmb = new TMB;
                        $tmb->product_id = $data['product_id'];
                        $tmb->ac_type_id = $data['aircraft_type']['id'];
                        $tmb->market_share = $data['market_share'];
                        $tmb->remarks = $data['remark'];
                        $tmb->maintenance_id = $data['maintenance_id']['id'];
                        $tmb->save();

                        $prospect_tmb = new ProspectTMB;
                        $prospect_tmb->prospect_id = $prospect->id;
                        $prospect_tmb->tmb_id = $tmb->id;
                        $prospect_tmb->save();
                    }
                }
            } else if ($transaction_type == 3) {
                foreach ($request->pbth as $product) {
                    foreach ($product['target'] as $target) {
                        $pbth = new PBTH;
                        $pbth->month = $target['month'];
                        $pbth->rate = $target['rate'];
                        $pbth->flight_hour = $target['flight_hour'];
                        $pbth->save();

                        $prospect_pbth = new ProspectPBTH;
                        $prospect_pbth->prospect_id = $prospect->id;
                        $prospect_pbth->pbth_id = $pbth->id;
                        $prospect_pbth->product_id = $product['product_id'];
                        $prospect_pbth->ac_type_id = $product['aircraft_type_id'];
                        $prospect_pbth->market_share = $target['rate'] * $target['flight_hour'];
                        $prospect_pbth->save();
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$p_type} {$t_type} Prospect created successfully",
                'data' => $prospect,
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
        $user = auth()->user();
        $customer = Customer::find($id);

        if ($user->hasRole('AMS')) {
            $amsCustomers = $customer->amsCustomers;
            foreach ($amsCustomers as $item) {
                if ($item->ams_id == $user->ams->id) {
                    $ams = true;
                } else {
                    $ams = false;
                }
            }
        } else {
            $ams = true;
        }

        if (!$customer || !$ams) {
            return response()->json([
                'message' => 'Data not found!',
            ], 404);
        }

        $data = Prospect::with(
                        'transactionType',
                        'prospectType',
                        'strategicInitiative',
                        'pm',
                        'amsCustomer',
                        'sales',
                        'prospectTmb',
                        'prospectPbth',
                        'amsCustomer.customer',
                        'amsCustomer.area',
                        'amsCustomer.ams',
                        'prospectTmb.tmb',
                        'prospectPbth.pbth',
                        'prospectPbth.product',
                        'prospectPbth.acType',
                        )->whereHas('amsCustomer', function ($query) use ($customer) {
                            $query->where('customer_id', $customer->id);
                        })->get();

        $market_share = Prospect::marketShareByCustomer($id);
        $total_sales = Sales::totalSalesByCustomer($id);

        return response()->json([
            'message' => 'Success Get Prospect By Customer!',
            'data' => [
                'prospect' => $data,
                'marketShare' => $market_share,
                'salesPlan' => $total_sales,
                'deviation' => $market_share - $total_sales,
            ]
        ], 200);
    }

    public function pbth($id)
    {
        $prospect = Prospect::findOrFail($id);
        $market_share = $prospect->market_share;
        $sales_plan = $prospect->sales_plan;
        
        $data = PBTH::whereHas('prospectPbth', function ($query) use ($id) {
                    $query->where('prospect_id', $id);
                })->get();
        
        return response()->json([
            'data' => [
                'customer' => $prospect->amsCustomer->customer,
                'registration' => $prospect->registration,
                'market_share' => $market_share,
                'sales_plan' => $sales_plan,
                'deviation' => $market_share - $sales_plan,
                'prospect' => $data
            ],
        ], 200);
    }

    public function tmb($id)
    {
        $prospect = Prospect::findOrFail($id);
        $market_share = $prospect->market_share;
        $sales_plan = $prospect->sales_plan;

        $data =  TMB::whereHas('prospectTmb', function ($query) use ($id) {
                    $query->where('prospect_id', $id);
                })->get();

        return response()->json([
            'data' => [
                'customer' => $prospect->amsCustomer->customer,
                'registration' => $prospect->registration,
                'market_share' => $market_share,
                'sales_plan' => $sales_plan,
                'deviation' => $market_share - $sales_plan,
                'prospect' => $data
            ],
        ], 200);
    }
}
