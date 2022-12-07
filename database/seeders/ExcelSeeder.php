<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\{
    Customer,
    TransactionType,
    ProspectType,
    StrategicInitiative,
    User,
    AMSCustomer,
    Area,
    AMS,
    Prospect,
    TMB,
    PBTH,
    Maintenance,
    AircraftType,
    Product,
    Component,
    Engine,
    APU,
    Sales,
    SalesLevel,
    SalesRequirement,
    SalesReject,
    Hangar,
    Line,
    CancelCategory,
    IGTE,
    Learning,
};

class ExcelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
            0 =>    'customer_name', -> customers, sales
            1 =>    'product_name', -> products, tmb, pbth, sales
            2 =>    'transaction_type', -> transaction_types, prospects
            3 =>    'prospect_year', -> propsects
            4 =>    'customer_group_type', -> customers
            5 =>    'prospect_type', -> prospect_types, prospects
            6 =>    'strategic_initiative', -> strategic_initiatives, prospects
            7 =>    'sales_type', -> sales
            8 =>    'project_manager', -> users, prospects
            9 =>    'hangar_name', -> sales
            10 =>   'line_name', -> sales
            11 =>   'ams_unit', -> users
            12 =>   'ams_initial', -> ams
            13 =>   'ams_area', -> ams_customers
            14 =>   'customer_country', -> customers
            15 =>   'customer_region', -> countries
            16 =>   'ac/eng/apu/comp', -> ac_type_id, engine_id, apu_id, component_id, tmb, pbth, sales
            17 =>   'aircraft_registration', -> sales
            18 =>   'maintenance_event', -> tnb, sales
            19 =>   'market_share', -> tmb, pbth
            20 =>   'sales_plan_fixed', -> sales
            21 =>   'sales_plan_updated', -> sales
            22 =>   'tmb_remarks', -> tmb
            23 =>   'so_number', -> sales
            24 =>   'start_date', -> sales
            25 =>   'tat', -> sales
            26 =>   'end_date', -> sales
            27 =>   'sales_level', -> sales
            28 =>   'sales_status', -> sales
            29 =>   'cancel_category', -> sales_rejects
            30 =>   'cancel_reason', -> sales_rejects
        */

        $csv_file = fopen(base_path("database/data/migrasi_2022_nodec.csv"), "r");

        $first_line = true;
        while (($data = fgetcsv($csv_file, 0, ",")) !== FALSE) {
            if (!$first_line) {
                try {
                    DB::beginTransaction();

                    $customer = Customer::firstWhere('name', $data['0']);
                    if (!$customer) { 
                        $group_type = ($data['4'] == 'GA') ? 0 : 1;
                        $customer = new Customer;
                        $customer->name = $data['0'];
                        $customer->group_type = $group_type;
                        $customer->save();
                    }

                    $ams = AMS::firstWhere('initial', $data['12']);
                    if (!$ams) {
                        $ams = new AMS;
                        $ams->user_id = null;
                        $ams->initial = $data['12'];
                        $ams->save();
                    }

                    $area = Area::firstWhere('name', $data['13']);
                    if (!$area) {
                        $area = new Area;
                        $area->name = $data['13'];
                        $area->save();
                    }

                    $ams_customer = AMSCustomer::where('customer_id', $customer->id)
                                                ->where('area_id', $area->id)
                                                ->where('ams_id', $ams->id)
                                                ->first();
                    if (!$ams_customer) {
                        $ams_customer = new AMSCustomer;
                        $ams_customer->customer_id = $customer->id;
                        $ams_customer->area_id = $area->id;
                        $ams_customer->ams_id = $ams->id;
                        $ams_customer->save();
                    }

                    $product = Product::firstWhere('name', $data['1']);
                    if (!$product) {
                        $product = new Product;
                        $product->name = $data['1'];
                        $product->save();
                    }

                    if ($data['2'] == 'TMB') {
                        $transaction_type = 1;
                    } else if ($data['2'] == 'PBTH') {
                        $transaction_type = 3;
                    }

                    $prospect = new Prospect;
                    $prospect->year = $data['3'];
                    $prospect->transaction_type_id = $transaction_type;
                    $prospect->prospect_type_id = 1;
                    $prospect->strategic_initiative_id = null;
                    $prospect->pm_id = null;
                    $prospect->ams_customer_id = $ams_customer->id;
                    $prospect->save();

                    $ac_type = AircraftType::firstWhere('name', $data['16']);
                    $igte = IGTE::firstWhere('name', $data['16']);
                    $learning = Learning::firstWhere('name', $data['16']);
                    $engine = Engine::firstWhere('name', $data['16']);
                    $apu = Apu::firstWhere('name', $data['16']);
                    $component = Component::firstWhere('name', $data['16']);

                    $maintenance = Maintenance::firstWhere('name', $data['18']);
                    if (!$maintenance) {
                        $maintenance = new Maintenance;
                        $maintenance->name = $data['18'];
                        $maintenance->save();
                    }

                    $market_share = $data['19'];
                    $remarks = $data['22'];

                    if ($prospect->transaction_type_id == 1) {
                        $tmb = new TMB;
                        $tmb->prospect_id = $prospect->id;
                        $tmb->product_id = $product->id;
                        $tmb->ac_type_id = $ac_type->id ?? null;
                        $tmb->igte_id = $igte->id ?? null;
                        $tmb->learning_id = $learning->id ?? null;
                        $tmb->component_id = $component->id ?? null;
                        $tmb->engine_id = $engine->id ?? null;
                        $tmb->apu_id = $apu->id ?? null;
                        $tmb->maintenance_id = $maintenance->id;
                        $tmb->market_share = $market_share;
                        $tmb->remarks = $remarks ?? null;
                        $tmb->save();
                    } else if ($prospect->transaction_type_id == 3) {
                        $start_date = strtotime($data['24']);
                        $month = Carbon::parse($start_date)->format('F');

                        $pbth = new PBTH;
                        $pbth->prospect_id = $prospect->id;
                        $pbth->product_id = $product->id;
                        $pbth->ac_type_id = $ac_type->id ?? null;
                        $pbth->month = $month;
                        $pbth->rate = null;
                        $pbth->flight_hour = null;
                        $pbth->market_share = $market_share;
                        $pbth->save();
                    }

                    $ac_reg = empty($data['17']) ? null : $data['17'];
                    $sales_updated = $data['21'];
                    $tat =  $data['25'];
                    $start_date = Carbon::parse(strtotime($data['24']))->format('Y-m-d');
                    $end_date = Carbon::parse(strtotime($data['26']))->format('Y-m-d');
                    $so_number = empty($data['23']) ? null : $data['23'];

                    $sales_type = $data['7'];
                    $is_rkap = ($sales_type == 'RKAP') ? 1 : 0;

                    $sales = new Sales;
                    $sales->customer_id = $customer->id;
                    $sales->prospect_id = $prospect->id;
                    $sales->transaction_type_id = $transaction_type;
                    $sales->ac_reg = $ac_reg;
                    $sales->value = $sales_updated;
                    $sales->maintenance_id = $maintenance->id;
                    $sales->tat =  $tat;
                    $sales->start_date = $start_date;
                    $sales->end_date = $end_date;
                    $sales->so_number = $so_number;
                    $sales->hangar_id = null;
                    $sales->line_id = null;
                    $sales->product_id = $product->id;
                    $sales->ac_type_id = $ac_type->id ?? null;
                    $sales->igte_id = $igte->id ?? null;
                    $sales->learning_id = $learning->id ?? null;
                    $sales->component_id = $component->id ?? null;
                    $sales->engine_id = $engine->id ?? null;
                    $sales->apu_id = $apu->id ?? null;
                    $sales->is_rkap = $is_rkap;
                    $sales->ams_id = $ams->id;
                    $sales->save();

                    $level = $data['27'];

                    if ($data['28'] == 'Open') {
                        $status = 1;
                    } else if ($data['28'] == 'Closed - In') {
                        $status = 2;
                    } else if ($data['28'] == 'Closed - Sales') {
                        $status = 3;
                    } else if ($data['28'] == 'Cancelled') {
                        $status = 4;
                    } 

                    $sales_level = new SalesLevel;
                    $sales_level->sales_id = $sales->id;
                    $sales_level->level_id = $level;
                    $sales_level->status = $status;
                    $sales_level->save();

                    if ($level == 1) {
                        if ($status == 3) {
                            for ($i = 1; $i <= 10; $i++) { 
                                $requirement = new SalesRequirement;
                                $requirement->sales_id = $sales->id;
                                $requirement->requirement_id = $i;
                                $requirement->status = 1;
                                $requirement->save();
                            }
                        } else {
                            for ($i = 1; $i <= 10; $i++) { 
                                $requirement = new SalesRequirement;
                                $requirement->sales_id = $sales->id;
                                $requirement->requirement_id = $i;
                                $requirement->status = in_array($i, [9, 10]) ? 0 : 1;
                                $requirement->save();
                            }
                        }
                    } else if ($level == 2) {
                        for ($i = 1; $i <= 10; $i++) { 
                            $requirement = new SalesRequirement;
                            $requirement->sales_id = $sales->id;
                            $requirement->requirement_id = $i;
                            $requirement->status = in_array($i, [8, 9, 10]) ? 0 : 1;
                            $requirement->save();
                        }
                    }else {
                        if ($transaction_type != 3) {
                            for ($i = 1; $i <= 10; $i++) { 
                                $requirement = new SalesRequirement;
                                $requirement->sales_id = $sales->id;
                                $requirement->requirement_id = $i;
                                $requirement->status = in_array($i, [1, 5]) ? 1 : 0;
                                $requirement->save();
                            }
                        } else {
                            for ($i = 1; $i <= 10; $i++) { 
                                $requirement = new SalesRequirement;
                                $requirement->sales_id = $sales->id;
                                $requirement->requirement_id = $i;
                                $requirement->status = in_array($i, [9, 10]) ? 0 : 1;
                                $requirement->save();
                            }
                        }
                    }

                    if ($status == 4) {
                        $cancel_category = CancelCategory::firstWhere('name', $data['29']);
                        if (!$cancel_category) {
                            $cancel_category = new CancelCategory;
                            $cancel_category->name = $data['29'];
                            $cancel_category->save();
                        }
    
                        $cancel_reason = $data['30'];
    
                        $sales_cancel = new SalesReject;
                        $sales_cancel->sales_id = $sales->id;
                        $sales_cancel->category_id = $cancel_category->id;
                        $sales_cancel->reason = $cancel_reason;
                        $sales_cancel->save();
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();

                    dd($e);
                }
            }
            $first_line = false;
        }
        fclose($csv_file);
    }
}
