<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use Carbon\Carbon;
use App\Models\Product;

class DashboardController extends Controller
{
    private $array_months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    public function area()
    {
        $user = auth()->user();

        $areas = ['I', 'II', 'III', 'KAM'];
        $array_target = [];
        $array_progress = [];
        $array_percentage = [];

        for ($i = 0; $i < count($areas); $i++) {
            $target = (float)number_format((Sales::user($user)->rkap()->area($areas[$i])->sum('value') / 1000000), 2);
            $progress = (float)number_format((Sales::user($user)->rkap()->area($areas[$i])->level(1)->clean()->sum('value') / 1000000), 2);
            $percentage = ($target == 0) ? 0 : (float)number_format((($progress / $target) * 100), 2);

            $array_target[] = $target;
            $array_progress[] = $progress;
            $array_percentage[] = $areas[$i]." (". $percentage ."%)";
        }

        $data = [
            'pie' => $array_target,
            'bar' => [
                'target' => $array_target,
                'progress' => $array_progress,
                'percentage' => $array_percentage,
            ],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data succesfully',
            'data' => $data,
        ], 200);
    }

    public function group()
    {
        $user = auth()->user();

        $groups = [0, 1];
        $group_names = ['GA', 'NGA'];
        $array_target = [];
        $array_progress = [];
        $array_percentage = [];

        for ($i = 0; $i < count($groups); $i++) {
            $target = (float)number_format((Sales::user($user)->rkap()->groupType($groups[$i])->sum('value') / 1000000), 2);
            $progress = (float)number_format((Sales::user($user)->rkap()->groupType($groups[$i])->level(1)->clean()->sum('value') / 1000000), 2);
            $percentage = ($target == 0) ? 0 : (float)number_format((($progress / $target) * 100), 2);

            $array_target[] = $target;
            $array_progress[] = $progress;
            $array_percentage[] = $group_names[$i]." (". $percentage ."%)";;
        }

        $data = [
            'pie' => $array_target,
            'bar' => [
                'target' => $array_target,
                'progress' => $array_progress,
                'percentage' => $array_percentage,
            ],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data succesfully',
            'data' => $data,
        ], 200);
    }

    public function product()
    {
        $user = auth()->user();

        $products = [9, 8, 7, 6, 5, 4, 2, 1, 3];
        $array_target = [];
        $array_progress = [];
        $array_percentage = [];

        for ($i = 0; $i < count($products); $i++) {
            $target = (float)number_format((Sales::user($user)->rkap()->product($products[$i])->sum('value') / 1000000), 2);
            $progress = (float)number_format((Sales::user($user)->rkap()->product($products[$i])->level(1)->clean()->sum('value') / 1000000), 2);
            $percentage = ($target == 0) ? 0 : (float)number_format((($progress / $target) * 100), 2);
            $product = Product::find($products[$i])->name;

            $array_target[] = $target;
            $array_progress[] = $progress;
            $array_percentage[] = $product." (". $percentage ."%)";
        }

        $data = [
            'pie' => $array_target,
            'bar' => [
                'target' => $array_target,
                'progress' => $array_progress,
                'percentage' => $array_percentage,
            ],
        ];
        
        return response()->json([
            'success' => true,
            'message' => 'Retrieve data succesfully',
            'data' => $data,
        ], 200);
    }

    public function rofoTotalMonth()
    {
        $user = auth()->user();

        $year = date('Y');
        $month = date('m');
        $day = date('d');

        $array_target = [];
        $array_progress = [];
        $array_percentage = [];
        $array_gap = [];

        for ($i = 1; $i <= 12; $i++) {
            if ($i < $month) {
                $total_days = Carbon::create()->day(1)->month($i)->year($year)->endOfMonth()->format('d');

                $date_range = [
                    'start_date' => Carbon::create()->day(1)->month($i)->year($year)->format('Y-m-d'),
                    'end_date' => Carbon::create()->day($total_days)->month($i)->year($year)->format('Y-m-d'),
                ];
            } else if ($i == $month) {
                $date_range = [
                    'start_date' => Carbon::create()->day(1)->month($i)->year($year)->format('Y-m-d'),
                    'end_date' => Carbon::create()->day($day)->month($i)->year($year)->format('Y-m-d'),
                ];
            }

            if ($i > $month) {
                $target = 0;
                $progress = 0;
                $percentage = 0;
            } else {
                $target = (float)number_format((Sales::user($user)->rkap()->month($i)->clean()->sum('value') / 1000000), 2);
                $progress = (float)number_format((Sales::user($user)->rkap()->filter($date_range)->level(1)->clean()->sum('value') / 1000000), 2);
                $percentage = $target == 0 ? 0 : (float)number_format((($progress / $target) * 100), 2);
            }

            $array_target[] = $target;
            $array_progress[] = $progress;
            $array_percentage[] = $this->array_months[$i-1]." (". $percentage ."%)";
            $array_gap[] = $target == 0 ? 0 : (float)number_format(($target - $progress), 2);
        }

        $data = [
            'target' => $array_target,
            'progress' => $array_progress,
            'percentage' => $array_percentage,
            'gap' => $array_gap,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data succesfully',
            'data' => $data,
        ], 200);
    }

    public function rofoTotalYear()
    {
        $user = auth()->user();

        $target = (float)number_format((Sales::user($user)->rkap()->thisYear()->clean()->sum('value') / 1000000), 2);
        $progress = (float)number_format((Sales::user($user)->rkap()->thisYear()->level(1)->clean()->sum('value') / 1000000), 2);
        $percentage = $target == 0 ? 0 : (float)number_format((($progress / $target) * 100), 2);
        $gap = $target == 0 ? 0 : (float)number_format(($target - $progress), 2);

        $data = [
            'target' => [$target],
            'progress' => [$progress],
            'percentage' => ["RoFo YTD (${percentage}%)"],
            'gap' => [$gap],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data succesfully',
            'data' => $data,
        ], 200);
    }

    public function rofoGarudaMonth()
    {
        $user = auth()->user();

        $year = date('Y');
        $month = date('m');
        $day = date('d');

        $array_target = [];
        $array_progress = [];
        $array_percentage = [];
        $array_gap = [];

        for ($i = 1; $i <= 12; $i++) {
            if ($i < $month) {
                $total_days = Carbon::create()->day(1)->month($i)->year($year)->endOfMonth()->format('d');

                $date_range = [
                    'start_date' => Carbon::create()->day(1)->month($i)->year($year)->format('Y-m-d'),
                    'end_date' => Carbon::create()->day($total_days)->month($i)->year($year)->format('Y-m-d'),
                ];
            } else if ($i == $month) {
                $date_range = [
                    'start_date' => Carbon::create()->day(1)->month($i)->year($year)->format('Y-m-d'),
                    'end_date' => Carbon::create()->day($day)->month($i)->year($year)->format('Y-m-d'),
                ];
            }

            if ($i > $month) {
                $target = 0;
                $progress = 0;
                $percentage = 0;
            } else {
                $target = (float)number_format((Sales::user($user)->rkap()->customerName('Garuda Indonesia')->month($i)->clean()->sum('value') / 1000000), 2);
                $progress = (float)number_format((Sales::user($user)->rkap()->customerName('Garuda Indonesia')->filter($date_range)->level(1)->clean()->sum('value') / 1000000), 2);
                $percentage = $target == 0 ? 0 : (float)number_format((($progress / $target) * 100), 2);
            }

            $array_target[] = $target;
            $array_progress[] = $progress;
            $array_percentage[] = $this->array_months[$i-1]." (". $percentage ."%)";
            $array_gap[] = $target == 0 ? 0 : (float)number_format(($target - $progress), 2);
        }

        $data = [
            'target' => $array_target,
            'progress' => $array_progress,
            'percentage' => $array_percentage,
            'gap' => $array_gap,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data succesfully',
            'data' => $data,
        ], 200);
    }

    public function rofoGarudaYear()
    {
        $user = auth()->user();

        $target = (float)number_format((Sales::user($user)->rkap()->customerName('Garuda Indonesia')->thisYear()->clean()->sum('value') / 1000000), 2);
        $progress = (float)number_format((Sales::user($user)->rkap()->customerName('Garuda Indonesia')->thisYear()->level(1)->clean()->sum('value') / 1000000), 2);
        $percentage = $target == 0 ? 0 : (float)number_format((($progress / $target) * 100), 2);
        $gap = $target == 0 ? 0 : (float)number_format(($target - $progress), 2);

        $data = [
            'target' => [$target],
            'progress' => [$progress],
            'percentage' => ["RoFo YTD (${percentage}%)"],
            'gap' => [$gap],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data succesfully',
            'data' => $data,
        ], 200);
    }

    public function rofoCitilinkMonth()
    {
        $user = auth()->user();

        $year = date('Y');
        $month = date('m');
        $day = date('d');

        $array_target = [];
        $array_progress = [];
        $array_percentage = [];
        $array_gap = [];

        for ($i = 1; $i <= 12; $i++) {
            if ($i < $month) {
                $total_days = Carbon::create()->day(1)->month($i)->year($year)->endOfMonth()->format('d');

                $date_range = [
                    'start_date' => Carbon::create()->day(1)->month($i)->year($year)->format('Y-m-d'),
                    'end_date' => Carbon::create()->day($total_days)->month($i)->year($year)->format('Y-m-d'),
                ];
            } else if ($i == $month) {
                $date_range = [
                    'start_date' => Carbon::create()->day(1)->month($i)->year($year)->format('Y-m-d'),
                    'end_date' => Carbon::create()->day($day)->month($i)->year($year)->format('Y-m-d'),
                ];
            }

            if ($i > $month) {
                $target = 0;
                $progress = 0;
                $percentage = 0;
            } else {
                $target = (float)number_format((Sales::user($user)->rkap()->customerName('Citilink Indonesia')->month($i)->clean()->sum('value') / 1000000), 2);
                $progress = (float)number_format((Sales::user($user)->rkap()->customerName('Citilink Indonesia')->filter($date_range)->level(1)->clean()->sum('value') / 1000000), 2);
                $percentage = $target == 0 ? 0 : (float)number_format((($progress / $target) * 100), 2);
            }

            $array_target[] = $target;
            $array_progress[] = $progress;
            $array_percentage[] = $this->array_months[$i-1]." (". $percentage ."%)";
            $array_gap[] = $target == 0 ? 0 : (float)number_format(($target - $progress), 2);
        }

        $data = [
            'target' => $array_target,
            'progress' => $array_progress,
            'percentage' => $array_percentage,
            'gap' => $array_gap,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data succesfully',
            'data' => $data,
        ], 200);
    }

    public function rofoCitilinkYear()
    {
        $user = auth()->user();

        $target = (float)number_format((Sales::user($user)->rkap()->customerName('Citilink Indonesia')->thisYear()->clean()->sum('value') / 1000000), 2);
        $progress = (float)number_format((Sales::user($user)->rkap()->customerName('Citilink Indonesia')->thisYear()->level(1)->clean()->sum('value') / 1000000), 2);
        $percentage = $target == 0 ? 0 : (float)number_format((($progress / $target) * 100), 2);
        $gap = $target == 0 ? 0 : (float)number_format(($target - $progress), 2);

        $data = [
            'target' => [$target],
            'progress' => [$progress],
            'percentage' => ["RoFo YTD (${percentage}%)"],
            'gap' => [$gap],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data succesfully',
            'data' => $data,
        ], 200);
    }
}
